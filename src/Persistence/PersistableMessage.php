<?php

namespace Robertbaelde\Eventsourcing\Persistence;

class PersistableMessage
{
    public function __construct(
        public readonly string $messageId,
        public readonly string $aggregateRootId,
        public readonly string $aggregateRootIdType,
        public readonly string $eventPayload,
        public readonly string $eventType,
        public readonly int $aggregateVersion,
        public readonly string $headerPayload,
        public readonly string $recordedAt,
    )
    {

    }
}
