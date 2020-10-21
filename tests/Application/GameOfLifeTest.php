<?php

declare(strict_types=1);

namespace Tests\Application;

use Chuano\GameOfLife\Application\GameOfLife;
use Chuano\GameOfLife\Domain\Universe;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class GameOfLifeTest extends TestCase
{
    /** @test */
    public function it_should_kill_cell_whith_less_than_2_and_more_than_3_alive_neighbors()
    {
        /*
         [ ][*][ ][ ][ ]     [ ][ ][ ][ ][ ]
         [ ][*][ ][ ][ ]     [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]  => [ ][ ][ ][ ][ ]
         [ ][ ][*][*][*]     [ ][ ][ ][*][ ]
         [ ][ ][ ][ ][ ]     [ ][ ][ ][ ][ ]
         */
        $universe = new Universe(5);
        $universe->getCell(0, 1)->markAlive();
        $universe->getCell(1, 1)->markAlive();
        $universe->getCell(3, 2)->markAlive();
        $universe->getCell(3, 3)->markAlive();
        $universe->getCell(3, 4)->markAlive();

        $game = new GameOfLife($universe);
        $game->iterate();
        $this->assertFalse($universe->getCell(0, 1)->isAlive());
        $this->assertFalse($universe->getCell(1, 1)->isAlive());
        $this->assertFalse($universe->getCell(3, 2)->isAlive());
        $this->assertTrue($universe->getCell(3, 3)->isAlive());
        $this->assertFalse($universe->getCell(3, 4)->isAlive());
    }

    /** @test */
    public function it_should_resurrect_cell_whith_3_alive_neighbors()
    {
        /*
         [ ][*][ ][*][ ]     [ ][ ][*][ ][ ]
         [ ][ ][*][ ][ ]     [ ][ ][*][ ][ ]
         [ ][ ][ ][ ][ ]  => [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]     [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]     [ ][ ][ ][ ][ ]
         */
        $universe = new Universe(5);
        $universe->getCell(0, 1)->markAlive();
        $universe->getCell(0, 3)->markAlive();
        $universe->getCell(1, 2)->markAlive();

        $game = new GameOfLife($universe);
        $game->iterate();
        $this->assertFalse($universe->getCell(0, 1)->isAlive());
        $this->assertFalse($universe->getCell(0, 3)->isAlive());
        $this->assertTrue($universe->getCell(0, 2)->isAlive());
        $this->assertTrue($universe->getCell(1, 2)->isAlive());
    }

    /** @test */
    public function it_should_count_5_neighbors()
    {
        /*
         [ ][*][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         */
        $universe = new Universe(5);
        $universe->getCell(0, 1)->markAlive();
        $game = new GameOfLife($universe);

        $reflection = new ReflectionClass(GameOfLife::class);
        $method = $reflection->getMethod('getNeighbors');
        $method->setAccessible(true);
        $neighbors = $method->invokeArgs($game, [1]);
        $this->assertCount(5, $neighbors);
    }

    /** @test */
    public function it_should_count_3_neighbors_in_left_top_corner()
    {
        /*
         [*][*][ ][ ][ ]
         [*][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         */
        $universe = new Universe(5);
        $universe->getCell(0, 0)->markAlive();
        $universe->getCell(0, 1)->markAlive();
        $universe->getCell(1, 0)->markAlive();
        $game = new GameOfLife($universe);

        $reflection = new ReflectionClass(GameOfLife::class);
        $method = $reflection->getMethod('getNeighbors');
        $method->setAccessible(true);
        $neighbors = $method->invokeArgs($game, [0]);
        $this->assertCount(3, $neighbors);
    }

    /** @test */
    public function it_should_count_3_neighbors_in_right_top_corner()
    {
        /*
         [ ][ ][ ][*][*]
         [ ][ ][ ][ ][*]
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         */
        $universe = new Universe(5);
        $universe->getCell(0, 3)->markAlive();
        $universe->getCell(0, 4)->markAlive();
        $universe->getCell(1, 4)->markAlive();
        $game = new GameOfLife($universe);

        $reflection = new ReflectionClass(GameOfLife::class);
        $method = $reflection->getMethod('getNeighbors');
        $method->setAccessible(true);
        $neighbors = $method->invokeArgs($game, [4]);
        $this->assertCount(3, $neighbors);
    }

    /** @test */
    public function it_should_count_3_neighbors_in_left_bottom_corner()
    {
        /*
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [*][ ][ ][ ][ ]
         [*][*][ ][ ][ ]
         */
        $universe = new Universe(5);
        $universe->getCell(3, 0)->markAlive();
        $universe->getCell(4, 0)->markAlive();
        $universe->getCell(4, 1)->markAlive();
        $game = new GameOfLife($universe);

        $reflection = new ReflectionClass(GameOfLife::class);
        $method = $reflection->getMethod('getNeighbors');
        $method->setAccessible(true);
        $neighbors = $method->invokeArgs($game, [20]);
        $this->assertCount(3, $neighbors);
    }

    /** @test */
    public function it_should_count_3_neighbors_in_right_bottom_corner()
    {
        /*
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][*]
         [ ][ ][ ][*][*]
         */
        $universe = new Universe(5);
        $universe->getCell(3, 4)->markAlive();
        $universe->getCell(4, 3)->markAlive();
        $universe->getCell(4, 4)->markAlive();
        $game = new GameOfLife($universe);

        $reflection = new ReflectionClass(GameOfLife::class);
        $method = $reflection->getMethod('getNeighbors');
        $method->setAccessible(true);
        $neighbors = $method->invokeArgs($game, [25]);
        $this->assertCount(3, $neighbors);
    }

    /** @test */
    public function it_should_count_5_neighbors_in_center_bottom()
    {
        /*
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][*][*][*][ ]
         */
        $universe = new Universe(5);
        $universe->getCell(4, 1)->markAlive();
        $universe->getCell(4, 2)->markAlive();
        $universe->getCell(4, 3)->markAlive();
        $game = new GameOfLife($universe);

        $reflection = new ReflectionClass(GameOfLife::class);
        $method = $reflection->getMethod('getNeighbors');
        $method->setAccessible(true);
        $neighbors = $method->invokeArgs($game, [22]);
        $this->assertCount(5, $neighbors);
    }

    /** @test */
    public function it_should_count_8_neighbors_in_center()
    {
        /*
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][ ][ ][ ][ ]
         [ ][ ][*][ ][ ]
         [ ][*][*][*][ ]
         */
        $universe = new Universe(5);
        $universe->getCell(3, 2)->markAlive();
        $universe->getCell(4, 1)->markAlive();
        $universe->getCell(4, 2)->markAlive();
        $universe->getCell(4, 3)->markAlive();
        $game = new GameOfLife($universe);

        $reflection = new ReflectionClass(GameOfLife::class);
        $method = $reflection->getMethod('getNeighbors');
        $method->setAccessible(true);
        $neighbors = $method->invokeArgs($game, [17]);
        $this->assertCount(8, $neighbors);
    }
}

