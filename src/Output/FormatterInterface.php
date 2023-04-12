<?php

namespace Czim\PHPUnitPrinter\Output;

use Czim\PHPUnitPrinter\Context\TestClassInformation;
use Czim\PHPUnitPrinter\Context\TestInformation;

interface FormatterInterface
{
    public function testClassStart(TestClassInformation $class, int $current, int $total): string;
    public function testFinish(TestInformation $test): string;
    public function unexpectedOutput(string $output): string;
    public function deprecation(string $file, int $line, string $message): string;
}
