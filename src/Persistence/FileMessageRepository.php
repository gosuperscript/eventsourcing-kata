<?php

namespace Robertbaelde\Eventsourcing\Persistence;

use Robertbaelde\Eventsourcing\AggregateRootId;
use Robertbaelde\Eventsourcing\Serialization\ClassToTypeMapper;

class FileMessageRepository implements MessageRepository
{
    public function __construct(
        private string $filePath,
        private ClassToTypeMapper $aggregateUuidTypeMap)
    {

    }
    public function getMessages(AggregateRootId $aggregateRootId): array
    {
        $messages = explode(PHP_EOL, @file_get_contents($this->getFilePath($aggregateRootId)));
        $messages = array_filter($messages, function ($message) {
            return !empty($message);
        });

        $messages = array_map(function (string $message) {
            return $this->jsonToMessage($message);
        }, $messages);

        usort($messages, function (PersistableMessage $a, PersistableMessage $b) {
            return $a->aggregateVersion <=> $b->aggregateVersion;
        });

        return $messages;
    }

    public function persistMessages(AggregateRootId $aggregateRootId, PersistableMessage ...$message): void
    {
        $contentToAppend = '';
        foreach ($message as $message) {
            $contentToAppend .= $this->messageToJson($message) . PHP_EOL;
        }
        file_put_contents($this->getFilePath($aggregateRootId), $contentToAppend , FILE_APPEND | LOCK_EX);
    }

    private function getFilePath(AggregateRootId $aggregateRootId)
    {
        $path = $this->filePath . '/'. $this->aggregateUuidTypeMap->FQNToString($aggregateRootId);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        return $path.'/'.$aggregateRootId->toString().'.txt';
    }

    private function messageToJson(PersistableMessage $message)
    {
        return json_encode([
            'messageId' => $message->messageId,
            'aggregateRootId' => $message->aggregateRootId,
            'aggregateRootIdType' => $message->aggregateRootIdType,
            'eventPayload' => $message->eventPayload,
            'eventType' => $message->eventType,
            'aggregateVersion' => $message->aggregateVersion,
            'headerPayload' => $message->headerPayload,
            'recordedAt' => $message->recordedAt,
        ]);
    }

    private function jsonToMessage(string $json): PersistableMessage
    {
        $data = json_decode($json, true);
        return new PersistableMessage(
            $data['messageId'],
            $data['aggregateRootId'],
            $data['aggregateRootIdType'],
            $data['eventPayload'],
            $data['eventType'],
            $data['aggregateVersion'],
            $data['headerPayload'],
            $data['recordedAt']
        );
    }
}
