<?php

namespace Robertbaelde\Eventsourcing\ConsumptionTest;

class CouldntDebitBecauseInsufficientFunds
{

    public function __construct(int $debitAmount, int $saldo)
    {
    }
}
