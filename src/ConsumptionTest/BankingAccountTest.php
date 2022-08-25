<?php

namespace Robertbaelde\Eventsourcing\ConsumptionTest;

use PHPUnit\Framework\TestCase;

class BankingAccountTest extends TestCase
{
    /** @test */
    public function it_can_handle_credit_transaction()
    {
        $bankAccountNumber = BankAccountNumber::fromString('1234567890');
        $aggregate = new BankAccount($bankAccountNumber);
        $aggregate->credit(100);

        $events = $aggregate->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertEquals(new AccountCredited(100), $events[0]);
    }

    /** @test */
    public function it_can_handle_debit_transactions()
    {
        $bankAccountNumber = BankAccountNumber::fromString('1234567890');
        $aggregate = new BankAccount($bankAccountNumber);
        $aggregate->credit(100);
        $aggregate->releaseEvents();

        $aggregate->debit(50);

        $events = $aggregate->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertEquals(new AccountDebited(50), $events[0]);
    }
}
