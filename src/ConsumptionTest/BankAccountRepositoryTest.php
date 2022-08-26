<?php

namespace Robertbaelde\Eventsourcing\ConsumptionTest;

use PHPUnit\Framework\TestCase;
use Robertbaelde\Eventsourcing\Persistence\InMemoryMessageRepository;
use Robertbaelde\Eventsourcing\Persistence\MessageIdGenerator;
use Robertbaelde\Eventsourcing\Serialization\DefaultEventWrapper;
use Robertbaelde\Eventsourcing\Serialization\DefaultMessageSerializer;
use Robertbaelde\Eventsourcing\Serialization\FullClassPathMapper;

class BankAccountRepositoryTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_an_empty_aggregate()
    {
        $repository = new BankAccountRepository(
            new InMemoryMessageRepository(),
            new DefaultEventWrapper(new MessageIdGenerator()),
            new DefaultMessageSerializer(
                eventTypeMapper: new FullClassPathMapper(),
                aggregateRootIdTypeMapper: new FullClassPathMapper()
            ),
        );
        $bankAccount = $repository->retrieveByAggregateId(BankAccountNumber::fromString('1234567890'));

        $this->assertInstanceOf(BankAccount::class, $bankAccount);
    }

    /** @test */
    public function it_can_persist_and_retrieve_a_bank_account()
    {
        $id = BankAccountNumber::fromString('1234567890');
        $bankAccount = new BankAccount($id);
        $bankAccount->credit(10);

        $repository = new BankAccountRepository(
            new InMemoryMessageRepository(),
            new DefaultEventWrapper(new MessageIdGenerator()),
            new DefaultMessageSerializer(
                eventTypeMapper: new FullClassPathMapper(),
                aggregateRootIdTypeMapper: new FullClassPathMapper()
            ),
        );
        $repository->persist($bankAccount);

        $this->assertEquals($bankAccount, $repository->retrieveByAggregateId($id));
    }
}
