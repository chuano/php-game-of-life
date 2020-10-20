<?php

declare(strict_types=1);

namespace Chuano\GameOfLife\Domain;

class Cell
{
    private bool $alive;

    public function __construct(?bool $alive = null)
    {
        $this->alive = $alive !== null ? $alive : false;
    }

    public function markAlive()
    {
        $this->alive = true;
    }

    public function markDead()
    {
        $this->alive = false;
    }

    public function isAlive(): bool
    {
        return $this->alive;
    }
}