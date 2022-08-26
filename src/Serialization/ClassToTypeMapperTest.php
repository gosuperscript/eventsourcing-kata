<?php

namespace Robertbaelde\Eventsourcing\Serialization;

use PHPUnit\Framework\TestCase;

abstract class ClassToTypeMapperTest extends TestCase
{
    /** @test */
    public function it_can_map_a_class_to_type()
    {
        $mapper = $this->getMapper();
        $string = $mapper->FQNToString(new Foo());
        $this->assertIsString($string);
        $fqn = $mapper->StringToFQN($string);
        $this->assertInstanceOf(Foo::class, new $fqn());
    }

    public abstract function getMapper(): ClassToTypeMapper;
}

class Foo
{

}
