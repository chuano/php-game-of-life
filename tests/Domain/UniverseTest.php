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
        $this->assertCount(12, $universe->getCells());
    }

    /** @test */
    public function it_should_create_universe_grid_with_same_rows_and_columns_if_no_columns_defined()
    {
        $universe = new Universe(4);
        $this->assertCount(16, $universe->getCells());
    }

    /** @test */
    public function it_should_create_universe_with_cells()
    {
        $universe = new Universe(4, 3);
        $this->assertInstanceOf(Cell::class, $universe->getCells()[0]);
    }

    /** @test */
    public function it_should_get_cell_index_1()
    {
        /*
        [ ][*][ ][ ][ ]
        [ ][ ][ ][ ][ ]
        [ ][ ][ ][ ][ ]
        [ ][ ][ ][ ][ ]
        [ ][ ][ ][ ][ ]
        */
        $universe = new Universe(5);
        $universe->getCells()[1]->markAlive();
        $this->assertInstanceOf(Cell::class, $universe->getCell(0, 1));
        $this->assertTrue($universe->getCell(0, 1)->isAlive());
    }

    /** @test */
    public function it_should_get_cell_index_6()
    {
        /*
        [ ][ ][ ][ ][ ]
        [ ][*][ ][ ][ ]
        [ ][ ][ ][ ][ ]
        [ ][ ][ ][ ][ ]
        [ ][ ][ ][ ][ ]
        */
        $universe = new Universe(5);
        $universe->getCells()[6]->markAlive();
        $this->assertInstanceOf(Cell::class, $universe->getCell(1, 1));
        $this->assertTrue($universe->getCell(1, 1)->isAlive());
    }

    /** @test */
    public function it_should_get_cell_index_11()
    {
        /*
        [ ][ ][ ][ ][ ][ ][ ][ ][ ][ ]
        [ ][*][ ][ ][ ][ ][ ][ ][ ][ ]
        [ ][ ][ ][ ][ ][ ][ ][ ][ ][ ]
        [ ][ ][ ][ ][ ][ ][ ][ ][ ][ ]
        [ ][ ][ ][ ][ ][ ][ ][ ][ ][ ]
        */
        $universe = new Universe(5, 10);
        $universe->getCells()[11]->markAlive();
        $this->assertInstanceOf(Cell::class, $universe->getCell(1, 1));
        $this->assertTrue($universe->getCell(1, 1)->isAlive());
    }
}