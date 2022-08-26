<?php

namespace Robertbaelde\Eventsourcing\Serialization;

use Robertbaelde\Eventsourcing\Message;
use Robertbaelde\Eventsourcing\Persistence\PersistableMessage;

interface SerializeMessage
{
    public function toPersistableMessage(Message $message): PersistableMessage;

    public function fromPersistableMessage(PersistableMessage $message): Message;
}
