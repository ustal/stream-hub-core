<?php

namespace Ustal\StreamHub\Component\Model;

final readonly class StreamParticipant
{
    public function __construct(
        public readonly string $userId,
        public readonly ?string $displayName,
        public readonly bool $active,
        public readonly \DateTimeImmutable $createdAt,
        public readonly array $settings = [],
        public readonly ?\DateTimeImmutable $leftAt = null,
        public readonly ?\DateTimeImmutable $lastReadAt = null,
    ) {}
}
