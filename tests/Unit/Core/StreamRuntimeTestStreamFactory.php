<?php

namespace Ustal\StreamHub\Tests\Unit\Core;

use Ustal\StreamHub\Component\Model\Stream;
use Ustal\StreamHub\Component\Model\StreamEventCollection;
use Ustal\StreamHub\Component\Model\StreamParticipant;

final class StreamRuntimeTestStreamFactory
{
    public static function create(string $id): Stream
    {
        return new Stream(
            id: $id,
            participants: [
                new StreamParticipant(
                    userId: 'user-1',
                    displayName: 'John Smith',
                    active: true,
                    createdAt: new \DateTimeImmutable('2026-04-07T12:00:00+00:00'),
                ),
            ],
            events: new StreamEventCollection(),
            createdAt: new \DateTimeImmutable('2026-04-07T12:00:00+00:00'),
            updatedAt: new \DateTimeImmutable('2026-04-07T12:00:00+00:00'),
        );
    }
}
