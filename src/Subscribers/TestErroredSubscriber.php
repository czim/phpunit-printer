<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Subscribers;

use Czim\PHPUnitPrinter\Context\TestContext;
use PHPUnit\Event\Test\Errored;
use PHPUnit\Event\Test\ErroredSubscriber;

class TestErroredSubscriber implements ErroredSubscriber
{
    public function __construct(protected readonly TestContext $context)
    {
    }

    public function notify(Errored $event): void
    {
        $this->context->markTestErrored();
    }
}
