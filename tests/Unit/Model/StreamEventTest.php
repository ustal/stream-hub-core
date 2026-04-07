<?php

namespace Ustal\StreamHub\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Enum\StreamEventType;
use Ustal\StreamHub\Component\Model\StreamEvent;

class StreamEventTest extends TestCase
{
    public function testItStoresImmutableEventData(): void
    {
        $createdAt = new \DateTimeImmutable('2026-04-07T10:00:00+00:00');
        $event = new StreamEvent(
            id: 'event-1',
            streamId: 'stream-1',
            userId: 'user-1',
            type: StreamEventType::MESSAGE,
            content: 'Hello world',
            createdAt: $createdAt,
        );

        $this->assertSame('event-1', $event->id);
        $this->assertSame('stream-1', $event->streamId);
        $this->assertSame('user-1', $event->userId);
        $this->assertSame(StreamEventType::MESSAGE, $event->type);
        $this->assertSame('Hello world', $event->content);
        $this->assertSame($createdAt, $event->createdAt);
    }

    public function testUserIdCanBeNullForSystemEvents(): void
    {
        $event = new StreamEvent(
            id: 'event-2',
            streamId: 'stream-1',
            userId: null,
            type: StreamEventType::SYSTEM,
            content: 'Participant joined',
            createdAt: new \DateTimeImmutable('2026-04-07T10:05:00+00:00'),
        );

        $this->assertNull($event->userId);
        $this->assertSame(StreamEventType::SYSTEM, $event->type);
    }
}
