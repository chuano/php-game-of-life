<?php

declare(strict_types=1);

namespace Tests\Domain;

use Chuano\GameOfLife\Domain\Cell;
use Chuano\GameOfLife\Domain\Universe;
use PHPUnit\Framework\TestCase;

class UniverseTest extends TestCase
{
    /** @test */
    public function it_should_create_universe_grid_with_rows_and_columns_defined()
    {
        $universe = new Universe(4, 3);
        $this->assertCount(4, $universe->getGrid());
        $this->assertCount(3, $universe->getGrid()[0]);
    }

    /** @test */
    public function it_should_create_universe_grid_with_same_rows_and_columns_if_no_columns_defined()
    {
        $universe = new Universe(4);
        $this->assertCount(4, $universe->getGrid()[0]);
        $this->assertCount(4, $universe->getGrid());
    }

    /** @test */
    public function it_should_create_universe_with_cells()
    {
        $universe = new Universe(4, 3);
        $this->assertInstanceOf(Cell::class, $universe->getGrid()[0][0]);
    }
}