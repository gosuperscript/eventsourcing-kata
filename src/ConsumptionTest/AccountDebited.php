<?php

namespace Robertbaelde\Eventsourcing\ConsumptionTest;

class AccountDebited
{

    public function __construct(public readonly int $amount)
    {
    }
}
