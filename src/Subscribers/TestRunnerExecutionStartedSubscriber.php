<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Subscribers;

use Czim\PHPUnitPrinter\Context\TestContext;
use PHPUnit\Event\TestRunner\ExecutionStarted;
use PHPUnit\Event\TestRunner\ExecutionStartedSubscriber;

class TestRunnerExecutionStartedSubscriber implements ExecutionStartedSubscriber
{
    public function __construct(protected readonly TestContext $context)
    {
    }

    public function notify(ExecutionStarted $event): void
    {
        $this->context->setTotalTests(
            $event->testSuite()->count()
        );
    }
}
