<?php

namespace Robertbaelde\Eventsourcing\ConsumptionTest;

class BankAccount
{
    private array $newEvents = [];

    public function __construct(private AggregateRootId $aggregateRootId)
    {
    }

    public function credit(int $amount)
    {
        $this->recordEvent(new AccountCredited($amount));
    }

    public function debit(int $amount)
    {
        $this->recordEvent(new AccountDebited($amount));
    }

    public function releaseEvents(): array
    {
        $events = $this->newEvents;
        $this->newEvents = [];
        return $events;
    }

    private function recordEvent(object $event)
    {
        $this->newEvents[] = $event;
    }

}
