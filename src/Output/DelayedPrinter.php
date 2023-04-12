<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Output;

use PHPUnit\TextUI\Output\Printer as PhpunitPrinterInterface;

class DelayedPrinter implements PrinterInterface
{
    /**
     * @var string[]
     */
    protected array $lines = [];

    public function __construct(protected readonly PhpunitPrinterInterface $printer)
    {
    }

    public function line(string $line): void
    {
        $this->lines[] = $line . PHP_EOL;
    }

    public function finalize(): void
    {
        foreach ($this->lines as $line) {
            $this->printer->print($line);
        }
    }
}
