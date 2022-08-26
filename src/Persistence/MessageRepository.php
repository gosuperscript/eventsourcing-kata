<?php

namespace Robertbaelde\Eventsourcing\Persistence;

use Robertbaelde\Eventsourcing\AggregateRootId;

interface MessageRepository
{
    public function getMessages(AggregateRootId $aggregateRootId): array;

    public function persistMessages(AggregateRootId $aggregateRootId, PersistableMessage ...$message): void;
}
