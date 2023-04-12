<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Output;

use Czim\PHPUnitPrinter\Context\TestClassInformation;
use Czim\PHPUnitPrinter\Context\TestInformation;
use Czim\PHPUnitPrinter\Enums\TestStatus;
use PHPUnit\Event\Telemetry\Duration;
use PHPUnit\Util\Color;

class Formatter implements FormatterInterface
{
    public function testClassStart(
        TestClassInformation $class,
        int $current,
        int $total,
    ): string {
        $padNumberTo = strlen((string) $total);

        return Color::colorize('fg-white', '[ ')
            . Color::colorize('fg-yellow', str_pad((string) $current, $padNumberTo, ' ', STR_PAD_LEFT))
            . Color::colorize('fg-white', ' / ' . $total . ' ]   ' . $class->class
        );
    }

    public function testFinish(TestInformation $test): string
    {
        $specialState = $this->getSpecialStateString($test->status);

        return $this->indent()
            . Color::colorize(
                $this->getColorStringForState($test->status),
                $this->getSymbolForState($test->status)
                . ' '
                . NameUtil::friendly($test->method)
                . (
                $specialState
                    ? ' <' . $specialState . '>'
                    : ''
                )
            )
            . $this->makeSlowTestSuffix($test->duration);
    }

    protected function makeSlowTestSuffix(Duration $duration): ?string
    {
        $seconds = $duration->nanoseconds() / 100000000;

        if ($seconds < $this->getSlowTestThresholdInSeconds()) {
            return null;
        }

        $color = $seconds > $this->getVerySlowTestThresholdInSeconds()
            ? 'fg-red'
            : 'fg-yellow';

        return Color::colorize($color, sprintf(' [%.3fs]', $seconds));
    }

    public function unexpectedOutput(string $output): string
    {
        return '> ' . $output;
    }

    public function deprecation(string $file, int $line, string $message): string
    {
        return $this->indent() . $file . ':' . $line . ': ' . $message;
    }


    protected function getSpecialStateString(TestStatus $status): string
    {
        $noSpecialStringFor = [
            TestStatus::PASSED,
            TestStatus::FAILED,
            TestStatus::ERRORED,
            TestStatus::RUNNING,
        ];

        if (in_array($status, $noSpecialStringFor)) {
            return '';
        }

        return $status->friendly();
    }

    protected function getSymbolForState(TestStatus $status): string
    {
        return match ($status) {
            TestStatus::PASSED  => '✓',
            TestStatus::INCOMPLETE,
            TestStatus::SKIPPED,
            TestStatus::RISKY,
            TestStatus::WARNING => '-',
            TestStatus::ERRORED,
            TestStatus::FAILED  => 'x',
            TestStatus::RUNNING => '.',
        };
    }

    protected function getColorStringForState(TestStatus $status): string
    {
        return match ($status) {
            TestStatus::PASSED  => 'fg-green',
            TestStatus::INCOMPLETE,
            TestStatus::SKIPPED,
            TestStatus::WARNING => 'fg-yellow',
            TestStatus::ERRORED,
            TestStatus::FAILED  => 'fg-red',
            TestStatus::RISKY   => 'fg-magenta',
            TestStatus::RUNNING => 'fg-white',
        };
    }

    protected function indent(int $count = 1): string
    {
        return str_repeat('    ', $count);
    }

    protected function getSlowTestThresholdInSeconds(): float
    {
        return 0.5;
    }

    protected function getVerySlowTestThresholdInSeconds(): float
    {
        return 1.0;
    }
}
