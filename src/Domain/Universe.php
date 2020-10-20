<?php

declare(strict_types=1);

namespace Chuano\GameOfLife\Domain;

class Universe
{
    private array $grid;

    public function __construct(int $rows, ?int $columns = null)
    {
        $this->grid = [];
        $columns = $columns ?: $rows;

        for ($i = 0; $i < $rows; $i++) {
            $this->addNewRow($columns);
        }
    }

    private function addNewRow(int $columns): void
    {
        $row = [];
        for ($i = 0; $i < $columns; $i++) {
            $row[] = new Cell();
        }
        $this->grid[] = $row;
    }

    public function getGrid(): array
    {
        return $this->grid;
    }

    public function getCell(int $row, int $column): ?Cell
    {
        return $this->grid[$row][$column] ?? null;
    }

    public function countAliveCells(): int
    {
        $counter = 0;
        foreach ($this->getGrid() as $row) {
            $aliveCells = array_filter($row, fn(Cell $cell) => $cell->isAlive());
            $counter += count($aliveCells);
        }
        return $counter;
    }
}