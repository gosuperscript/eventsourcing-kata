<?php

namespace Robertbaelde\Eventsourcing\Serialization;

use Robertbaelde\Eventsourcing\AggregateRootId;
use Robertbaelde\Eventsourcing\Event;
use Robertbaelde\Eventsourcing\Message;
use Robertbaelde\Eventsourcing\Persistence\MessageIdGenerator;

class DefaultEventWrapper implements WrapEventInMessage
{
    public function __construct(
        private MessageIdGenerator $messageIdGenerator,
    )
    {
    }

    public function toMessage(Event $event, AggregateRootId $aggregateRootId): Message
    {
        return new Message(
            messageId: $this->messageIdGenerator->generate(),
            event: $event,
            aggregateRootId: $aggregateRootId,
            aggregateVersion: 1,
            headers: [],
            recordedAt: new \DateTimeImmutable(),
        );
    }

    public function fromMessageToEvent(Message $message): Event
    {
        return $message->event;
    }
}
