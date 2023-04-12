<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Subscribers;

use Czim\PHPUnitPrinter\Context\TestContext;
use PHPUnit\Event\Test\Passed;
use PHPUnit\Event\Test\PassedSubscriber;

class TestPassedSubscriber implements PassedSubscriber
{
    public function __construct(protected readonly TestContext $context)
    {
    }

    public function notify(Passed $event): void
    {
        $this->context->markTestPassed();
    }
}
