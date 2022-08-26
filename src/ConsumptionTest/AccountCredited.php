<?php

namespace Robertbaelde\Eventsourcing\ConsumptionTest;

use Robertbaelde\Eventsourcing\Event;

final class AccountCredited extends Event
{
    public function __construct(public readonly int $amount)
    {
    }

    public function toPayload(): array
    {
        return ['amount' => $this->amount];
    }

    public static function fromPayload(array $payload): static
    {
        return new self($payload['amount']);
    }
}
