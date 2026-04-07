<?php

namespace Ustal\StreamHub\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Enum\StreamEventType;
use Ustal\StreamHub\Component\Model\Stream;
use Ustal\StreamHub\Component\Model\StreamEvent;
use Ustal\StreamHub\Component\Model\StreamEventCollection;
use Ustal\StreamHub\Component\Model\StreamParticipant;

class StreamTest extends TestCase
{
    public function testItStoresParticipantsAndEvents(): void
    {
        $participant = new StreamParticipant(
            userId: 'user-1',
            displayName: 'John Smith',
            active: true,
            createdAt: new \DateTimeImmutable('2026-04-07T11:00:00+00:00'),
        );

        $event = new StreamEvent(
            id: 'event-1',
            streamId: 'stream-1',
            userId: 'user-1',
            type: StreamEventType::MESSAGE,
            content: 'Hello world',
            createdAt: new \DateTimeImmutable('2026-04-07T11:05:00+00:00'),
        );

        $createdAt = new \DateTimeImmutable('2026-04-07T11:00:00+00:00');
        $updatedAt = new \DateTimeImmutable('2026-04-07T11:05:00+00:00');

        $stream = new Stream(
            id: 'stream-1',
            participants: [$participant],
            events: new StreamEventCollection($event),
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );

        $this->assertSame('stream-1', $stream->id);
        $this->assertSame([$participant], $stream->participants);
        $this->assertCount(1, $stream->events);
        $this->assertSame($createdAt, $stream->createdAt);
        $this->assertSame($updatedAt, $stream->updatedAt);
    }
}
