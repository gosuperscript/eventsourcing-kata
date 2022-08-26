<?php

namespace Robertbaelde\Eventsourcing\Serialization;

interface ClassToTypeMapper
{
    public function FQNToString(object $fqn): string;

    public function StringToFQN(string $type): string;
}
