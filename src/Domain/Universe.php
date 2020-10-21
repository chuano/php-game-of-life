<?php

declare(strict_types=1);

namespace Chuano\GameOfLife\Domain;

class Universe
{
    private array $cells;
    private int $rows;
    private ?int $columns;

    public function __construct(int $rows, ?int $columns = null)
    {
        $this->cells = [];
        $columns = $columns ?: $rows;
        $this->rows = $rows;
        $this->columns = $columns;

        for ($i = 0; $i < ($rows * $columns); $i++) {
            $this->cells[] = new Cell();
        }
    }

    public function getRows(): int
    {
        return $this->rows;
    }

    public function getColumns(): int
    {
        return $this->columns;
    }

    public function getCell(int $row, int $column): ?Cell
    {
        $offset = $row * $this->columns;
        $index = $column + $offset;
        return $this->cells[$index] ?? null;
    }

    public function getCells(): array
    {
        return $this->cells;
    }

    public function countAliveCells(): int
    {
        return count(
            array_filter(
                $this->cells,
                fn(Cell $cell) => $cell->isAlive()
            )
        );
    }
}