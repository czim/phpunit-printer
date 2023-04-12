<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Subscribers;

use Czim\PHPUnitPrinter\Context\TestContext;
use PHPUnit\Event\Test\MarkedIncomplete;
use PHPUnit\Event\Test\MarkedIncompleteSubscriber;

class TestMarkedIncompleteSubscriber implements MarkedIncompleteSubscriber
{
    public function __construct(protected readonly TestContext $context)
    {
    }

    public function notify(MarkedIncomplete $event): void
    {
        $this->context->markTestIncomplete();
    }
}
