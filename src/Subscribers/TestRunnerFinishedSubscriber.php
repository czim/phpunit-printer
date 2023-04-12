<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Subscribers;

use Czim\PHPUnitPrinter\Context\TestContext;
use PHPUnit\Event\TestRunner\Finished;
use PHPUnit\Event\TestRunner\FinishedSubscriber;

class TestRunnerFinishedSubscriber implements FinishedSubscriber
{
    public function __construct(protected readonly TestContext $context)
    {
    }

    public function notify(Finished $event): void
    {
        $this->context->markRunnerFinished();
    }
}
