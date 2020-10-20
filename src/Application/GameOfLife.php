<?php

declare(strict_types=1);

namespace Chuano\GameOfLife\Application;

use Chuano\GameOfLife\Domain\Universe;
use Exception;

class GameOfLife
{
    private const MIN_NEIGHBORS_FOR_ALIVE = 2;
    private const MAX_NEIGHBORS_FOR_ALIVE = 3;
    private const NEIGHBORS_FOR_RESURRECTION = 3;

    private Universe $universe;

    public function __construct(Universe $universe)
    {
        $this->universe = $universe;
    }

    /**
     * @throws Exception
     */
    public function iterate(): void
    {
        if ($this->universe->countAliveCells() === 0) {
            throw new Exception('Nobody is alive');
        }

        $this->processCells(
            $this->getIndexesCellsToKill(),
            $this->getIndexesCellsToResurrect()
        );
    }

    public function getUniverse(): Universe
    {
        return $this->universe;
    }

    private function getIndexesCellsToKill(): array
    {
        $indexes = [];
        foreach ($this->universe->getGrid() as $rowNumber => $row) {
            $indexes = [
                ...$indexes,
                ...$this->getIndexesCellsToKillInRow($row, $rowNumber)
            ];
        }
        return $indexes;
    }

    private function getIndexesCellsToKillInRow(array $row, int $rowNumber): array
    {
        $indexes = [];
        foreach ($row as $columnNumber => $cell) {
            if ($this->countAliveNeigbors($rowNumber, $columnNumber) < self::MIN_NEIGHBORS_FOR_ALIVE) {
                $indexes[] = ['row' => $rowNumber, 'column' => $columnNumber];
            }
            if ($this->countAliveNeigbors($rowNumber, $columnNumber) > self::MAX_NEIGHBORS_FOR_ALIVE) {
                $indexes[] = ['row' => $rowNumber, 'column' => $columnNumber];
            }
        }
        return $indexes;
    }

    private function getIndexesCellsToResurrect(): array
    {
        $indexes = [];
        foreach ($this->universe->getGrid() as $rowNumber => $row) {
            $indexes = [
                ...$indexes,
                ...$this->getIndexesCellsToResurrectInRow($row, $rowNumber)
            ];
        }
        return $indexes;
    }

    private function getIndexesCellsToResurrectInRow(array $row, int $rowNumber): array
    {
        $indexes = [];
        foreach ($row as $columnNumber => $cell) {
            if ($this->countAliveNeigbors($rowNumber, $columnNumber) === self::NEIGHBORS_FOR_RESURRECTION) {
                $indexes[] = ['row' => $rowNumber, 'column' => $columnNumber];
            }
        }
        return $indexes;
    }

    private function countAliveNeigbors(int $rowNumber, int $columnNumber): int
    {
        $counter = 0;
        $firstColumn = $columnNumber > 0 ? $columnNumber - 1 : $columnNumber;
        $lastColumn = $columnNumber < count($this->universe->getGrid()[0]) - 1 ? $columnNumber + 1 : $columnNumber;

        // Row above
        for ($i = $firstColumn; $i <= $lastColumn; $i++) {
            $cell = $this->universe->getCell($rowNumber - 1, $i);
            if ($cell && $cell->isAlive()) {
                $counter++;
            }
        }

        // Row below
        for ($i = $firstColumn; $i <= $lastColumn; $i++) {
            $cell = $this->universe->getCell($rowNumber + 1, $i);
            if ($cell && $cell->isAlive()) {
                $counter++;
            }
        }

        // Previous column
        $cell = $this->universe->getCell($rowNumber, $columnNumber - 1);
        if ($cell && $cell->isAlive()) {
            $counter++;
        }

        // Next column
        $cell = $this->universe->getCell($rowNumber, $columnNumber + 1);
        if ($cell && $cell->isAlive()) {
            $counter++;
        }

        return $counter;
    }

    private function processCells(array $cellsToKill, array $cellsToResurrect)
    {
        foreach ($cellsToKill as $cell) {
            $this->universe->getGrid()[$cell['row']][$cell['column']]->markDead();
        }
        foreach ($cellsToResurrect as $cell) {
            $this->universe->getGrid()[$cell['row']][$cell['column']]->markAlive();
        }
    }
}