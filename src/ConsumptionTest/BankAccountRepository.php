<?php

namespace Robertbaelde\Eventsourcing\ConsumptionTest;

use Robertbaelde\Eventsourcing\AggregateRoot;
use Robertbaelde\Eventsourcing\AggregateRootId;
use Robertbaelde\Eventsourcing\Event;
use Robertbaelde\Eventsourcing\Persistence\MessageRepository;
use Robertbaelde\Eventsourcing\Persistence\PersistableMessage;
use Robertbaelde\Eventsourcing\Serialization\SerializeMessage;
use Robertbaelde\Eventsourcing\Serialization\WrapEventInMessage;

class BankAccountRepository
{
//    protected $aggregateRootClass = BankAccount::class;

    public function __construct(
        private MessageRepository $messageRepository,
        private WrapEventInMessage $mapEventToMessageTransformer,
        private SerializeMessage $serializeMessage
    )
    {
    }


    public function retrieveByAggregateId(AggregateRootId $aggregateRootId): AggregateRoot
    {
        $pesistableMessages = $this->messageRepository->getMessages($aggregateRootId);
        $events = array_map(function (PersistableMessage $message) {
            $message = $this->serializeMessage->fromPersistableMessage($message);
            return $this->mapEventToMessageTransformer->fromMessageToEvent($message);
        }, $pesistableMessages);

        return BankAccount::fromEvents($aggregateRootId, $events);
    }

    public function persist(AggregateRoot $bankAccount)
    {
        $events = $bankAccount->releaseEvents();
        $this->messageRepository->persistMessages($bankAccount->aggregateRootId, ...array_map(fn(Event $event) => $this->serializeMessage->toPersistableMessage(
            $this->mapEventToMessageTransformer->toMessage($event, $bankAccount->aggregateRootId)
        ), $events));
    }
}
