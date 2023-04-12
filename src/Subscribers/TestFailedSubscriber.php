<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Subscribers;

use Czim\PHPUnitPrinter\Context\TestContext;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\FailedSubscriber;

class TestFailedSubscriber implements FailedSubscriber
{
    public function __construct(protected readonly TestContext $context)
    {
    }

    public function notify(Failed $event): void
    {
        $this->context->markTestFailed();
    }
}
