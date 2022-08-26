<?php

namespace Robertbaelde\Eventsourcing\Serialization;

use Robertbaelde\Eventsourcing\AggregateRootId;
use Robertbaelde\Eventsourcing\Event;
use Robertbaelde\Eventsourcing\Message;

interface WrapEventInMessage
{
    public function toMessage(Event $event, AggregateRootId $aggregateRootId): Message;

    public function fromMessageToEvent(Message $message): Event;
}
