<?php

namespace Robertbaelde\Eventsourcing\ConsumptionTest;

class AccountCredited
{
    public function __construct(public readonly int $amount)
    {
    }
}
