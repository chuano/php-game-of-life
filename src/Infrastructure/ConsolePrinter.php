<?php

declare(strict_types=1);

namespace Chuano\GameOfLife\Infrastructure;

use Chuano\GameOfLife\Domain\Universe;

class ConsolePrinter
{
    public function printUniverse(Universe $universe, int $iteration): void
    {
        system('clear');
        $columnCounter = 0;
        foreach ($universe->getCells() as $cell) {
            $columnCounter++;
            echo $cell->isAlive() ? '[X]' : ' _ ';
            if ($columnCounter === $universe->getColumns()) {
                $columnCounter = 0;
                echo PHP_EOL;
            }
        }
        echo $this->separator($universe->getColumns()) . PHP_EOL;
        echo '#' . $iteration . PHP_EOL;
    }

    private function separator(int $size): string
    {
        return str_repeat('===', $size);
    }
}