<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;

final readonly class TestCommand implements StreamCommandInterface
{
    public function __construct(public string $payload)
    {
    }
}
