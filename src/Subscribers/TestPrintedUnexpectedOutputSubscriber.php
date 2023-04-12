<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Subscribers;

use Czim\PHPUnitPrinter\Context\TestContext;
use PHPUnit\Event\Test\PrintedUnexpectedOutput;
use PHPUnit\Event\Test\PrintedUnexpectedOutputSubscriber;

class TestPrintedUnexpectedOutputSubscriber implements PrintedUnexpectedOutputSubscriber
{
    public function __construct(protected readonly TestContext $context)
    {
    }

    public function notify(PrintedUnexpectedOutput $event): void
    {
        $this->context->markUnexpectedOutput($event->output());
    }
}
