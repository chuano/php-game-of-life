<?php

declare(strict_types=1);

namespace Chuano\GameOfLife\Infrastructure;

use Chuano\GameOfLife\Domain\Universe;

class ConsolePrinter
{
    public function printUniverse(Universe $universe, int $iteration): void
    {
        system('clear');
        foreach ($universe->getGrid() as $row) {
            foreach ($row as $cell) {
                echo $cell->isAlive() ? '[X]' : ' _ ';
            }
            echo PHP_EOL;
        }
        echo $this->separator(count($universe->getGrid()[0])) . PHP_EOL;
        echo '#' . $iteration . PHP_EOL;
    }

    private function separator(int $size): string
    {
        return str_repeat('===', $size);
    }
}