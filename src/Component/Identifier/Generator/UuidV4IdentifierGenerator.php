<?php

namespace Ustal\StreamHub\Component\Identifier\Generator;

use Ustal\StreamHub\Component\Identifier\IdentifierGeneratorInterface;

final class UuidV4IdentifierGenerator implements IdentifierGeneratorInterface
{
    public function generate(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);

        return $this->formatUuid($bytes);
    }

    private function formatUuid(string $bytes): string
    {
        $hex = bin2hex($bytes);

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12),
        );
    }
}
