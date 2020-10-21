<?php

declare(strict_types=1);

namespace Tests\Application;

use Chuano\GameOfLife\Application\UniverseGenerator;
use Exception;
use PHPUnit\Framework\TestCase;

class UniverseGeneratorTest extends TestCase
{
    /** @test */
    public function it_should_generate_universe_with_size_and_density_defined()
    {
        $axisSize = 10;
        $density = 0.5;
        $totalSize = $axisSize * $axisSize;
        $universeGenerator = new UniverseGenerator($axisSize, $axisSize, $density);
        $universe = $universeGenerator->generate();
        $this->assertCount($totalSize, $universe->getCells());
        $this->assertEquals(ceil($totalSize * $density), $universe->countAliveCells());
    }

    /** @test */
    public function it_should_throw_exception_if_axis_size_less_than_10()
    {
        $axisSize = 9;
        $density = 0.5;
        $this->expectException(Exception::class);
        new UniverseGenerator($axisSize, $axisSize, $density);
    }

    /** @test */
    public function it_should_throw_exception_if_axis_size_more_than_100()
    {
        $axisSize = 101;
        $density = 0.5;
        $this->expectException(Exception::class);
        new UniverseGenerator($axisSize, $axisSize, $density);
    }

    /** @test */
    public function it_should_throw_exception_if_density_less_than_0dot1()
    {
        $axisSize = 10;
        $density = 0.09;
        $this->expectException(Exception::class);
        new UniverseGenerator($axisSize, $axisSize, $density);
    }

    /** @test */
    public function it_should_throw_exception_if_density_more_than_0dot9()
    {
        $axisSize = 10;
        $density = 0.91;
        $this->expectException(Exception::class);
        new UniverseGenerator($axisSize, $axisSize, $density);
    }
}