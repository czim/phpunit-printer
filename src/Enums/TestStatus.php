<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Enums;

enum TestStatus: string
{
    case ERRORED    = 'E';
    case FAILED     = 'F';
    case INCOMPLETE = 'I';
    case PASSED     = 'P';
    case RISKY      = 'R';
    case RUNNING    = '-';
    case SKIPPED    = 'S';
    case WARNING    = 'W';

    public function friendly(): string
    {
        return match ($this) {
            self::ERRORED    => 'ERROR',
            self::FAILED     => 'FAILED',
            self::INCOMPLETE => 'incomplete',
            self::PASSED     => 'pass',
            self::RISKY      => 'risky',
            self::RUNNING    => '...',
            self::SKIPPED    => 'skipped',
            self::WARNING    => 'warning',
        };
    }
}
