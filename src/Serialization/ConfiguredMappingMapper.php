<?php

namespace Robertbaelde\Eventsourcing\Serialization;

class ConfiguredMappingMapper implements ClassToTypeMapper
{
    public function __construct(private array $mappings)
    {
    }

    public function FQNToString(object $fqn): string
    {
        return array_key_exists(get_class($fqn), $this->mappings)
            ? $this->mappings[get_class($fqn)]
            : throw new \Exception('No mapping found for ' . get_class($fqn));
    }

    public function StringToFQN(string $type): string
    {
        return array_search($type, $this->mappings);
    }
}
