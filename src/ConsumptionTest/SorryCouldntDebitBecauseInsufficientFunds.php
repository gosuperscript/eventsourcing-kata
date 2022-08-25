<?php

namespace Robertbaelde\Eventsourcing\ConsumptionTest;

class SorryCouldntDebitBecauseInsufficientFunds extends \Exception
{
    public static function forAmountWithSaldo(int $amount, int $saldo)
    {
        return new self(sprintf('Could not debit %d because only %d is available', $amount, $saldo));
    }
}
