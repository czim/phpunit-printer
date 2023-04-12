<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Subscribers;

use Czim\PHPUnitPrinter\Context\TestContext;
use PHPUnit\Event\Test\WarningTriggered;
use PHPUnit\Event\Test\WarningTriggeredSubscriber;

class TestWarningTriggeredSubscriber implements WarningTriggeredSubscriber
{
    public function __construct(protected readonly TestContext $context)
    {
    }

    public function notify(WarningTriggered $event): void
    {
        $this->context->markTestWarning();
    }
}
