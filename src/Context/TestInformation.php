<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Context;

use Czim\PHPUnitPrinter\Enums\TestStatus;
use PHPUnit\Event\Telemetry\Duration;

class TestInformation
{
    public function __construct(
        public readonly string $method,
        public readonly int $testNumber,
        public TestStatus $status,
        public ?Duration $duration = null,
    ) {
    }
}
