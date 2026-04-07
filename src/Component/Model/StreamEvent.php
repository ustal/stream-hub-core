<?php

namespace Ustal\StreamHub\Component\Model;

use Ustal\StreamHub\Component\Enum\StreamEventType;

final readonly class StreamEvent
{
    public function __construct(
        public string $id,
        public string $streamId,
        public ?string $userId,
        public StreamEventType $type,
        public string $content,
        public \DateTimeImmutable $createdAt,
    ) {}
}
