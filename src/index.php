<?php

use Chuano\GameOfLife\Application\GameOfLife;
use Chuano\GameOfLife\Application\UniverseGenerator;
use Chuano\GameOfLife\Infrastructure\ConsolePrinter;

require __DIR__ . '/../vendor/autoload.php';

$generator = new UniverseGenerator($argv[1] ?? 20, $argv[2] ?? 20, $argv[3] ?? 0.5);
echo "Generating random universe...";
$universe = $generator->generate();
$manager = new GameOfLife($universe);
$printer = new ConsolePrinter();

$iteration = 0;
while (true) {
    try {
        $iteration++;
        $printer->printUniverse($universe, $iteration);
        $manager->iterate();
        //usleep(5000);
    } catch (Exception $exception) {
        echo $exception->getMessage() . PHP_EOL;
        break;
    }
}