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
            $this->getCellsToKill(),
            $this->getCellsToResurect()
        );
    }

    private function getCellsToKill(): array
    {
        return array_filter(
            $this->universe->getCells(),
            fn($index) => $this->hasTooFewOrTooManyNeighbors($index),
            ARRAY_FILTER_USE_KEY
        );
    }

    private function getCellsToResurect(): array
    {
        return array_filter(
            $this->universe->getCells(),
            fn($index) => $this->hasPerfectNeighbors($index),
            ARRAY_FILTER_USE_KEY
        );
    }

    private function hasTooFewOrTooManyNeighbors(int $currentIndex)
    {
        $neighbors = $this->getNeighbors($currentIndex);
        $aliveNeighbors = count(
            array_filter($neighbors, fn($cell) => $cell->isAlive())
        );
        return $aliveNeighbors < self::MIN_NEIGHBORS_FOR_ALIVE || $aliveNeighbors > self::MAX_NEIGHBORS_FOR_ALIVE;
    }

    private function hasPerfectNeighbors(int $currentIndex)
    {
        $neighbors = $this->getNeighbors($currentIndex);
        $aliveNeighbors = count(
            array_filter($neighbors, fn($cell) => $cell->isAlive())
        );
        return $aliveNeighbors === self::NEIGHBORS_FOR_RESURRECTION;
    }

    private function getNeighbors(int $currentIndex): array
    {
        return [
            ...$this->getTopCells($currentIndex),
            ...$this->getBottomCells($currentIndex),
            ...$this->getSameRowCells($currentIndex)
        ];
    }

    private function getSameRowCells(int $currentIndex): array
    {
        $cells = [];
        if (!$this->isFirstColumn($currentIndex)) {
            $cells[] = $this->universe->getCells()[$currentIndex - 1];
        }
        if (!$this->isLastColumn($currentIndex)) {
            $cells[] = $this->universe->getCells()[$currentIndex + 1];
        }
        return $cells;
    }

    private function getTopCells(int $currentIndex): array
    {
        if ($this->isFirstRow($currentIndex)) {
            return [];
        }

        if ($this->isFirstColumn($currentIndex)) {
            $firstTopCellIndex = $currentIndex - $this->universe->getColumns();
        } else {
            $firstTopCellIndex = $currentIndex - 1 - $this->universe->getColumns();
        }

        if ($this->isLastColumn($currentIndex)) {
            $lastTopCellIndex = $currentIndex - $this->universe->getColumns();
        } else {
            $lastTopCellIndex = $currentIndex + 1 - $this->universe->getColumns();
        }

        return $this->getCellRange($firstTopCellIndex, $lastTopCellIndex);
    }

    private function getBottomCells(int $currentIndex): array
    {
        if ($this->isLastRow($currentIndex)) {
            return [];
        }

        if ($this->isFirstColumn($currentIndex)) {
            $firstBottomCellIndex = $currentIndex + $this->universe->getColumns();
        } else {
            $firstBottomCellIndex = $currentIndex - 1 + $this->universe->getColumns();
        }

        if ($this->isLastColumn($currentIndex)) {
            $lastBottomCellIndex = $currentIndex + $this->universe->getColumns();
        } else {
            $lastBottomCellIndex = $currentIndex + 1 + $this->universe->getColumns();
        }

        return $this->getCellRange($firstBottomCellIndex, $lastBottomCellIndex);
    }

    private function getCurrentRow(int $currentIndex): int
    {
        if ($currentIndex === count($this->universe->getCells())) {
            $currentIndex--;
        }
        return (int)floor($currentIndex / $this->universe->getColumns());
    }

    private function getCurrentColumn(int $currentIndex): int
    {
        if ($currentIndex === count($this->universe->getCells())) {
            $currentIndex--;
        }
        return $currentIndex - ($this->universe->getColumns() * $this->getCurrentRow($currentIndex));
    }

    private function getLastRow(): int
    {
        return $this->universe->getRows() - 1;
    }

    private function getLastColumn(): int
    {
        return $this->universe->getColumns() - 1;
    }

    private function isFirstColumn(int $currentIndex): bool
    {
        return $this->getCurrentColumn($currentIndex) === 0;
    }

    private function isLastColumn(int $currentIndex): bool
    {
        return $this->getCurrentColumn($currentIndex) === $this->getLastColumn();
    }

    private function isFirstRow(int $currentIndex): bool
    {
        return $this->getCurrentRow($currentIndex) === 0;
    }

    private function isLastRow(int $currentIndex): bool
    {
        return $this->getCurrentRow($currentIndex) === $this->getLastRow();
    }

    private function getCellRange(int $firstIndex, int $lastIndex): array
    {
        $cells = [];
        for ($i = $firstIndex; $i <= $lastIndex; $i++) {
            $cells[] = $this->universe->getCells()[$i];
        }
        return $cells;
    }

    private function processCells(array $cellsToKill, array $cellsToResurrect)
    {
        foreach ($cellsToKill as $cell) {
            $cell->markDead();
        }
        foreach ($cellsToResurrect as $cell) {
            $cell->markAlive();
        }
    }
}