<?php

namespace Robertbaelde\Eventsourcing\ConsumptionTest;

use Robertbaelde\Eventsourcing\Event;

class CouldntDebitBecauseInsufficientFunds extends Event
{

    public function __construct(public readonly int $debitAmount, public readonly int $balance)
    {
    }


    public function toPayload(): array
    {
        return [
            'debitAmount' => $this->debitAmount,
            'balance' => $this->balance
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new self($payload['debitAmount'], $payload['balance']);
    }
}
