<?php

namespace Robertbaelde\Eventsourcing\Persistence;

use Ramsey\Uuid\Uuid;

class MessageIdGenerator
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
