<?php

namespace Czim\PHPUnitPrinter\Output;

interface PrinterInterface
{
    public function line(string $line): void;
    public function finalize(): void;
}
