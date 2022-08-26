<?php

namespace Robertbaelde\Eventsourcing\Persistence;

use Robertbaelde\Eventsourcing\AggregateRootId;

class InMemoryMessageRepository implements MessageRepository
{
    private array $messages = [];

    public function getMessages(AggregateRootId $aggregateRootId): array
    {
        $messages = $this->messages[$aggregateRootId->toString()] ?? [];
        usort($messages, function (PersistableMessage $a, PersistableMessage $b) {
            return $a->aggregateVersion <=> $b->aggregateVersion;
        });
        return $messages;
    }

    public function persistMessages(AggregateRootId $aggregateRootId, PersistableMessage ...$messages): void
    {
        foreach ($messages as $message) {
            $this->messages[$aggregateRootId->toString()][] = $message;
        }
    }
}
