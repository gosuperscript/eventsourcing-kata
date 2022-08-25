<?php

namespace Robertbaelde\Eventsourcing\ConsumptionTest;

use Robertbaelde\Eventsourcing\AggregateRoot;

class BankAccount extends AggregateRoot
{
    private int $saldo = 0;

    public function credit(int $amount): void
    {
        $this->recordEvent(new AccountCredited($amount));
    }

    /**
     * @throws SorryCouldntDebitBecauseInsufficientFunds
     */
    public function debit(int $amount): void
    {
        if($this->saldo - $amount < 0){
            $this->recordEvent(new CouldntDebitBecauseInsufficientFunds($amount, $this->saldo));
            throw SorryCouldntDebitBecauseInsufficientFunds::forAmountWithSaldo($amount, $this->saldo);
            return;
        }
        $this->recordEvent(new AccountDebited($amount));
    }

    protected function applyAccountCredited(AccountCredited $event): void
    {
        $this->saldo += $event->amount;
    }

    protected function applyAccountDebited(AccountDebited $event): void
    {
        $this->saldo -= $event->amount;
    }

}
