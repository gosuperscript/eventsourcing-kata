<?php

namespace Robertbaelde\Eventsourcing;

use Robertbaelde\Eventsourcing\ConsumptionTest\AggregateRootId;
use Robertbaelde\Eventsourcing\ConsumptionTest\BankAccountNumber;

abstract class AggregateRoot
{
    private array $newEvents = [];
    private $aggregateRootVersion = 0;

    public function __construct(public readonly AggregateRootId $aggregateRootId)
    {
    }

    public static function fromEvents(BankAccountNumber $bankAccountNumber, array $events)
    {
        $aggregateRoot = new static($bankAccountNumber);
        foreach ($events as $event) {
            $aggregateRoot->applyEvent($event);
        }
        return $aggregateRoot;
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
