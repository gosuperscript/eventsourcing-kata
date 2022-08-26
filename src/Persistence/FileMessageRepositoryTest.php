<?php

namespace Robertbaelde\Eventsourcing\Persistence;

use Robertbaelde\Eventsourcing\Serialization\ConfiguredMappingMapper;

class FileMessageRepositoryTest extends MessageRepositoryTest
{
    public function setUp(): void
    {
        array_map('unlink', glob("../../test-storage/stubAggregateRoot/*.*"));
        parent::setUp();
    }

    /** @test */
    public function it_returns_empty_aggregate_on_non_existing()
    {
        $messageRepo = $this->getMessageRepository();
        $this->assertCount(0, $messageRepo->getMessages(StubAggregateRootId::fromString('asdajshdb')));
    }

    public function getMessageRepository(): MessageRepository
    {
        return new FileMessageRepository(
            '../../test-storage',
            new ConfiguredMappingMapper([
                StubAggregateRootId::class => 'stubAggregateRoot'
            ])
        );
    }
}
