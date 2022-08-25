<?php

namespace Robertbaelde\Eventsourcing;

use Robertbaelde\Eventsourcing\ConsumptionTest\AggregateRootId;

abstract class AggregateRoot
{
    private array $newEvents = [];
    private $aggregateRootVersion = 0;

    public function __construct(private AggregateRootId $aggregateRootId)
    {

    }

    public function releaseEvents(): array
    {
        $events = $this->newEvents;
        $this->newEvents = [];
        return $events;
    }

    protected function recordEvent(object $event)
    {
        $this->newEvents[] = $event;
        $this->applyEvent($event);
    }

    private function applyEvent(object $event)
    {
        $parts = explode('\\', get_class($event));
        $methodName = 'apply' . end($parts);

        if (method_exists($this, $methodName)) {
            $this->{$methodName}($event);
        }

        ++$this->aggregateRootVersion;
    }
}
