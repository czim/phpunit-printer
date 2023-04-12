<?php

declare(strict_types=1);

namespace Czim\PHPUnitPrinter\Subscribers;

use Czim\PHPUnitPrinter\Context\TestContext;
use PHPUnit\Event\Test\PreparationStarted;
use PHPUnit\Event\Test\PreparationStartedSubscriber;

class TestPreparationStartedSubscriber implements PreparationStartedSubscriber
{
    public function __construct(protected readonly TestContext $context)
    {
    }

    public function notify(PreparationStarted $event): void
    {
        $this->context->markTestStarted(
            $event->test()->file(),
            $this->getClassNameFromId($event->test()->id()),
            $event->test()->name(),
        );
    }

    protected function getClassNameFromId(string $id): string
    {
        if (! str_contains($id, '::')) {
            return $id;
        }

        return substr($id, 0, strpos($id, '::'));
    }
}
