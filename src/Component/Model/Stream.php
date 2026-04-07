<?php

namespace Ustal\StreamHub\Component\Model;

final readonly class Stream
{
    public function __construct(
        public string $id,
        public array $participants,
        public StreamEventCollection $events,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
    ) {}
}
