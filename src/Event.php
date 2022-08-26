<?php

namespace Robertbaelde\Eventsourcing;

abstract class Event
{
    abstract public function toPayload(): array;

    abstract public static function fromPayload(array $payload): static;
}
