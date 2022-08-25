<?php

namespace Robertbaelde\Eventsourcing\ConsumptionTest;

class BankAccount
{
    private array $newEvents = [];
    private $aggregateRootVersion = 0;
    private int $saldo = 0;

    public function __construct(private AggregateRootId $aggregateRootId)
    {
    }

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

    public function releaseEvents(): array
    {
        $events = $this->newEvents;
        $this->newEvents = [];
        return $events;
    }

    private function recordEvent(object $event)
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
