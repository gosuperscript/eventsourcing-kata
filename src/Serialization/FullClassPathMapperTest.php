<?php

namespace Robertbaelde\Eventsourcing\Serialization;

class FullClassPathMapperTest extends ClassToTypeMapperTest
{
    public function getMapper(): ClassToTypeMapper
    {
        return new FullClassPathMapper();
    }
}
