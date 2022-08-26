<?php

namespace Robertbaelde\Eventsourcing\Serialization;

class ConfiguredMappingMapperTest extends ClassToTypeMapperTest
{
    public function getMapper(): ClassToTypeMapper
    {
        return new ConfiguredMappingMapper([
            Foo::class => 'Foo',
        ]);
    }
}
