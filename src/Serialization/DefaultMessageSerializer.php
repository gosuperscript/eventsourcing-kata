<?php

namespace Robertbaelde\Eventsourcing\Serialization;

use Robertbaelde\Eventsourcing\Message;
use Robertbaelde\Eventsourcing\Persistence\PersistableMessage;

class DefaultMessageSerializer implements SerializeMessage
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct(
        private ClassToTypeMapper $eventTypeMapper,
        private ClassToTypeMapper $aggregateRootIdTypeMapper,
    )
    {
    }

    public function toPersistableMessage(Message $message): PersistableMessage
    {
        return new PersistableMessage(
            messageId: $message->messageId,
            aggregateRootId: $message->aggregateRootId->toString(),
            aggregateRootIdType: $this->aggregateRootIdTypeMapper->FQNToString($message->aggregateRootId),
            eventPayload: json_encode($message->event->toPayload()),
            eventType: $this->eventTypeMapper->FQNToString($message->event),
            aggregateVersion: $message->aggregateVersion,
            headerPayload: json_encode($message->headers),
            recordedAt: $message->recordedAt->format(self::DATE_FORMAT),
        );
    }

    public function fromPersistableMessage(PersistableMessage $message): Message
    {

        return new Message(
            messageId: $message->messageId,
            event: $this->eventTypeMapper->stringToFQN($message->eventType)::fromPayload(json_decode($message->eventPayload, true)),
            aggregateRootId: $this->aggregateRootIdTypeMapper->stringToFQN($message->aggregateRootIdType)::fromString($message->aggregateRootId),
            aggregateVersion: $message->aggregateVersion,
            headers: json_decode($message->headerPayload, true),
            recordedAt: \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $message->recordedAt),
        );
    }
}
