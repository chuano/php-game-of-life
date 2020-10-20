<?php

declare(strict_types=1);

namespace Tests\Domain;

use Chuano\GameOfLife\Domain\Cell;
use PHPUnit\Framework\TestCase;

class CellTest extends TestCase
{
    /** @test */
    public function it_should_create_cell_dead_if_not_state_argument()
    {
        $cell = new Cell();
        $this->assertFalse($cell->isAlive());
    }

    /** @test */
    public function it_should_create_cell_alive_if_state_argument_is_true()
    {
        $cell = new Cell(true);
        $this->assertTrue($cell->isAlive());
    }

    /** @test */
    public function it_should_create_cell_alive_if_state_argument_is_false()
    {
        $cell = new Cell(false);
        $this->assertFalse($cell->isAlive());
    }

    /** @test */
    public function it_should_mark_cell_alive()
    {
        $cell = new Cell(false);
        $cell->markAlive();
        $this->assertTrue($cell->isAlive());
    }

    /** @test */
    public function it_should_mark_cell_dead()
    {
        $cell = new Cell(true);
        $cell->markDead();
        $this->assertFalse($cell->isAlive());
    }
}