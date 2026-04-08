<?php

namespace Ustal\StreamHub\Component\Identifier\Generator;

use Ustal\StreamHub\Component\Identifier\IdentifierGeneratorInterface;

final readonly class RandomHexIdentifierGenerator implements IdentifierGeneratorInterface
{
    public function __construct(private int $bytes = 16)
    {
        if ($bytes < 1) {
            throw new \InvalidArgumentException('Random hex identifier generator requires at least 1 byte.');
        }
    }

    public function generate(): string
    {
        return bin2hex(random_bytes($this->bytes));
    }
}
