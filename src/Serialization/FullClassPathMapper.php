<?php

namespace Robertbaelde\Eventsourcing\Serialization;

class FullClassPathMapper implements ClassToTypeMapper
{

    public function FQNToString(object $fqn): string
    {
        return get_class($fqn);
    }

    public function StringToFQN(string $type): string
    {
        return $type;
    }
}
