<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter;

use Czim\PHPUnitPrinter\Context\TestContext;
use Czim\PHPUnitPrinter\Output\DelayedPrinter;
use Czim\PHPUnitPrinter\Output\Formatter;
use Czim\PHPUnitPrinter\Output\FormatterInterface;
use Czim\PHPUnitPrinter\Output\InstantPrinter;
use Czim\PHPUnitPrinter\Output\PrinterInterface;
use Czim\PHPUnitPrinter\Subscribers\TestConsideredRiskySubscriber;
use Czim\PHPUnitPrinter\Subscribers\TestErroredSubscriber;
use Czim\PHPUnitPrinter\Subscribers\TestFailedSubscriber;
use Czim\PHPUnitPrinter\Subscribers\TestFinishedSubscriber;
use Czim\PHPUnitPrinter\Subscribers\TestMarkedIncompleteSubscriber;
use Czim\PHPUnitPrinter\Subscribers\TestPassedSubscriber;
use Czim\PHPUnitPrinter\Subscribers\TestPhpDeprecationTriggeredSubscriber;
use Czim\PHPUnitPrinter\Subscribers\TestPreparationStartedSubscriber;
use Czim\PHPUnitPrinter\Subscribers\TestPrintedUnexpectedOutputSubscriber;
use Czim\PHPUnitPrinter\Subscribers\TestRunnerExecutionStartedSubscriber;
use Czim\PHPUnitPrinter\Subscribers\TestRunnerFinishedSubscriber;
use Czim\PHPUnitPrinter\Subscribers\TestWarningTriggeredSubscriber;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnit\TextUI\Output\DefaultPrinter;

class PrintExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        if ($configuration->noOutput()) {
            return;
        }

        $context = new TestContext(
            $this->getBaseTestsPath(),
            $this->makeFormatter(),
            $this->makePrinter($configuration),
        );

        $facade->registerSubscribers(
            new TestConsideredRiskySubscriber($context),
            new TestErroredSubscriber($context),
            new TestFailedSubscriber($context),
            new TestFinishedSubscriber($context),
            new TestMarkedIncompleteSubscriber($context),
            new TestPassedSubscriber($context),
            new TestPhpDeprecationTriggeredSubscriber($context),
            new TestPreparationStartedSubscriber($context),
            new TestPrintedUnexpectedOutputSubscriber($context),
            new TestRunnerExecutionStartedSubscriber($context),
            new TestRunnerFinishedSubscriber($context),
            new TestWarningTriggeredSubscriber($context),
        );
    }

    protected function makeFormatter(): FormatterInterface
    {
        return new Formatter();
    }

    protected function makePrinter(Configuration $config): PrinterInterface
    {
        if ($config->noProgress()) {
            return new InstantPrinter(
                DefaultPrinter::standardOutput()
            );
        }

        return new DelayedPrinter(
            DefaultPrinter::standardOutput()
        );
    }

    protected function getBaseTestsPath(): string
    {
        $slash = DIRECTORY_SEPARATOR;

        return realpath(__DIR__ . "{$slash}..{$slash}..{$slash}..{$slash}") . $slash;
    }
}
