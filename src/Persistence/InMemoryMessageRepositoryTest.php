<?php

namespace Robertbaelde\Eventsourcing\Persistence;

class InMemoryMessageRepositoryTest extends MessageRepositoryTest
{

    public function getMessageRepository(): MessageRepository
    {
        return new InMemoryMessageRepository();
    }
}
