<?php

namespace Robertbaelde\Eventsourcing\ConsumptionTest;

use Ramsey\Uuid\Uuid;
use Robertbaelde\Eventsourcing\AggregateRootId;

final class BankAccountNumber extends AggregateRootId
{
    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
}
