<?php

namespace Ustal\StreamHub\Component\Guard;

final readonly class GuardDecision
{
    private function __construct(
        public bool $allowed,
        public ?string $reason = null,
    ) {}

    public static function allow(): self
    {
        return new self(true);
    }

    public static function deny(?string $reason = null): self
    {
        return new self(false, $reason);
    }
}
