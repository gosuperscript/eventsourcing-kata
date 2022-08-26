<?php

namespace Robertbaelde\Eventsourcing\Persistence;

use PHPUnit\Framework\TestCase;
use Robertbaelde\Eventsourcing\AggregateRootId;

abstract class MessageRepositoryTest extends TestCase
{
    /** @test */
    public function it_can_persist_messages()
    {
        $aggregateRootId = new StubAggregateRootId('foo');
        $repository = $this->getMessageRepository();

        $message = new PersistableMessage(
            messageId: 'ca40f0f9-b882-411d-9b77-a16d058af034',
            aggregateRootId: 'db615306-c3bf-46d5-845b-0cdc1542f538',
            aggregateRootIdType: 'BankAccountNumber',
            eventPayload: '{amount: 1}',
            eventType: 'AccountDebited',
            aggregateVersion: 1,
            headerPayload: '{}',
            recordedAt: '2022-01-01 10:00:00'
        );

        $repository->persistMessages($aggregateRootId, $message);

        $messages = $repository->getMessages($aggregateRootId);
        $this->assertCount(1, $messages);
        $this->assertEquals($message, $messages[0]);
    }

    /** @test */
    public function it_can_retrieve_messages_in_order()
    {
        $aggregateRootId = new StubAggregateRootId('foo');
        $repository = $this->getMessageRepository();

        $messageB = new PersistableMessage(
            messageId: 'ca40f0f9-b882-411d-9b77-a16d058af034',
            aggregateRootId: 'db615306-c3bf-46d5-845b-0cdc1542f538',
            aggregateRootIdType: 'BankAccountNumber',
            eventPayload: '{amount: 1}',
            eventType: 'AccountCredited',
            aggregateVersion: 2,
            headerPayload: '{}',
            recordedAt: '2022-01-01 11:00:00'
        );

        $messageA = new PersistableMessage(
            messageId: 'ca40f0f9-b882-411d-9b77-a16d058af034',
            aggregateRootId: 'db615306-c3bf-46d5-845b-0cdc1542f538',
            aggregateRootIdType: 'BankAccountNumber',
            eventPayload: '{amount: 1}',
            eventType: 'AccountDebited',
            aggregateVersion: 1,
            headerPayload: '{}',
            recordedAt: '2022-01-01 10:00:00'
        );

        $repository->persistMessages($aggregateRootId, $messageB, $messageA);

        $messages = $repository->getMessages($aggregateRootId);
        $this->assertCount(2, $messages);
        $this->assertEquals($messageA, $messages[0]);
        $this->assertEquals($messageB, $messages[1]);
    }

    abstract public function getMessageRepository(): MessageRepository;

}

class StubAggregateRootId extends AggregateRootId
{

}
