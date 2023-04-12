<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Subscribers;

use Czim\PHPUnitPrinter\Context\TestContext;
use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber;

class TestFinishedSubscriber implements FinishedSubscriber
{
    public function __construct(protected readonly TestContext $context)
    {
    }

    public function notify(Finished $event): void
    {
        $this->context->markTestFinished(
            $event->telemetryInfo()->durationSincePrevious(),
        );
    }
}
