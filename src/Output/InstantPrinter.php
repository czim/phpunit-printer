<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Output;

use PHPUnit\TextUI\Output\Printer as PhpunitPrinterInterface;

class InstantPrinter implements PrinterInterface
{
    public function __construct(protected readonly PhpunitPrinterInterface $printer)
    {
    }

    public function line(string $line): void
    {
        $this->printer->print($line . PHP_EOL);
    }

    public function finalize(): void
    {
    }
}
