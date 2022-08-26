<?php

namespace Robertbaelde\Eventsourcing;

abstract class AggregateRootId
{
    public function __construct(private string $id)
    {
    }

    public static function fromString(string $string): static
    {
        return new static($string);
    }

    public function toString(): string
    {
        return $this->id;
    }
}
