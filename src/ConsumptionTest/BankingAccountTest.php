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

    /** @test */
    public function it_doesnt_allow_debit_resulting_in_negative_saldo()
    {
        $bankAccountNumber = BankAccountNumber::fromString('1234567890');
        $aggregate = new BankAccount($bankAccountNumber);
        $aggregate->credit(40);
        $aggregate->releaseEvents();

        $exceptionThrown = false;
        try {
            $aggregate->debit(50);
        } catch (SorryCouldntDebitBecauseInsufficientFunds $e) {
            $exceptionThrown = true;
        }

        $events = $aggregate->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertEquals(new CouldntDebitBecauseInsufficientFunds(50, 40), $events[0]);

        $this->assertTrue($exceptionThrown);
    }

    /** @test */
    public function aggregate_root_can_be_reconstructed_from_events()
    {
        $bankAccountNumber = BankAccountNumber::fromString('1234567890');
        $aggregate = BankAccount::fromEvents($bankAccountNumber, [
            new AccountCredited(100),
            new AccountDebited(60),
        ]);

        $exceptionThrown = false;
        try {
            $aggregate->debit(50);
        } catch (SorryCouldntDebitBecauseInsufficientFunds $e) {
            $exceptionThrown = true;
        }

        $events = $aggregate->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertEquals(new CouldntDebitBecauseInsufficientFunds(50, 40), $events[0]);

        $this->assertTrue($exceptionThrown);
    }
}
