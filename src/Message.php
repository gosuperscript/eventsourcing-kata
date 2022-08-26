<?php

namespace Robertbaelde\Eventsourcing;

class Message
{
    public function __construct(
        public readonly string $messageId,
        public readonly Event $event,
        public readonly AggregateRootId $aggregateRootId,
        public readonly int $aggregateVersion,
        public readonly array $headers,
        public readonly \DateTimeImmutable $recordedAt,
    )
    {

    }
}
