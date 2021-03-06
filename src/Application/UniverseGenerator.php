<?php

declare(strict_types=1);

namespace Chuano\GameOfLife\Application;

use Chuano\GameOfLife\Domain\Cell;
use Chuano\GameOfLife\Domain\Universe;
use Exception;

class UniverseGenerator
{
    private const MIN_DENSITY = 0.1;
    private const MAX_DENSITY = 0.9;
    private const MIN_AXIS_SIZE = 10;
    private const MAX_AXIS_SIZE = 100;

    private Universe $universe;
    private int $rows;
    private int $columns;
    private float $aliveGoal;

    /**
     * @throws Exception
     */
    public function __construct(int $rows, int $columns, float $density)
    {
        if ($density < self::MIN_DENSITY || $density > self::MAX_DENSITY) {
            throw new Exception('Density must be between ' . self::MIN_DENSITY . ' and ' . self::MAX_DENSITY);
        }

        if ($rows < self::MIN_AXIS_SIZE || $rows > self::MAX_AXIS_SIZE) {
            throw new Exception('Rows size must be between ' . self::MIN_AXIS_SIZE . ' and ' . self::MAX_AXIS_SIZE);
        }

        if ($columns < self::MIN_AXIS_SIZE || $columns > self::MAX_AXIS_SIZE) {
            throw new Exception('Columns size must be between ' . self::MIN_AXIS_SIZE . ' and ' . self::MAX_AXIS_SIZE);
        }

        $this->rows = $rows;
        $this->columns = $columns;
        $this->aliveGoal = (float)ceil($rows * $columns * $density);
    }

    public function generate(): Universe
    {
        $this->universe = new Universe($this->rows, $this->columns);
        $aliveCellsCounter = 0;

        while ($aliveCellsCounter < $this->aliveGoal) {
            $this->markRandomAlive();
            $aliveCellsCounter++;
        }

        return $this->universe;
    }

    private function markRandomAlive(): void
    {
        $deadCells = $this->getDeadCells();
        $index = rand(0, count($deadCells) - 1);
        $deadCells[$index]->markAlive();
    }

    private function getDeadCells(): array
    {
        return array_values(
            array_filter(
                $this->universe->getCells(),
                fn(Cell $cell) => !$cell->isAlive()
            )
        );
    }
}