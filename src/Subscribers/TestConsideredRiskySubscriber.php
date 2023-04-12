<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Subscribers;

use Czim\PHPUnitPrinter\Context\TestContext;
use PHPUnit\Event\Test\ConsideredRisky;
use PHPUnit\Event\Test\ConsideredRiskySubscriber;

class TestConsideredRiskySubscriber implements ConsideredRiskySubscriber
{
    public function __construct(protected readonly TestContext $context)
    {
    }

    public function notify(ConsideredRisky $event): void
    {
        $this->context->markTestRisky();
    }
}
