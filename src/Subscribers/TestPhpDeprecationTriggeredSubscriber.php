<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Subscribers;

use Czim\PHPUnitPrinter\Context\TestContext;
use PHPUnit\Event\Test\PhpDeprecationTriggered;
use PHPUnit\Event\Test\PhpDeprecationTriggeredSubscriber;

class TestPhpDeprecationTriggeredSubscriber implements PhpDeprecationTriggeredSubscriber
{
    public function __construct(protected readonly TestContext $context)
    {
    }

    public function notify(PhpDeprecationTriggered $event): void
    {
        $this->context->markPhpDeprecation(
            $event->file(),
            $event->line(),
            $event->message(),
        );
    }
}
