<?php

namespace Ustal\StreamHub\Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Enum\StreamEventType;
use Ustal\StreamHub\Component\Model\Stream;
use Ustal\StreamHub\Component\Model\StreamEvent;
use Ustal\StreamHub\Component\Model\StreamParticipant;
use Ustal\StreamHub\Core\StreamRuntime;
use Ustal\StreamHub\Tests\Fake\InMemoryStreamBackend;
use Ustal\StreamHub\Tests\Fake\InMemoryUserContext;
use Ustal\StreamHub\Tests\Unit\Core\StreamRuntimeTestStreamFactory;

class StreamRuntimeTest extends TestCase
{
    public function testAppendEventPersistsAndRefreshesStream(): void
    {
        $backend = new InMemoryStreamBackend();
        $stream = StreamRuntimeTestStreamFactory::create('stream-1');
        $backend->seedStream($stream);

        $runtime = new StreamRuntime($stream, $backend, new InMemoryUserContext());
        $event = new StreamEvent(
            id: 'event-1',
            streamId: 'stream-1',
            userId: 'user-1',
            type: StreamEventType::MESSAGE,
            content: 'Hello',
            createdAt: new \DateTimeImmutable('2026-04-07T12:05:00+00:00'),
        );

        $appendedEvent = $runtime->appendEvent($event);

        $this->assertSame($event, $appendedEvent);
        $this->assertCount(1, $runtime->getEvents());
        $this->assertSame(1, $runtime->getUnreadEventCount());
    }

    public function testJoinReturnsUpdatedStreamAndMutatesRuntimeState(): void
    {
        $backend = new InMemoryStreamBackend();
        $stream = StreamRuntimeTestStreamFactory::create('stream-1');
        $backend->seedStream($stream);

        $runtime = new StreamRuntime($stream, $backend, new InMemoryUserContext());
        $participant = new StreamParticipant(
            userId: 'user-2',
            displayName: 'Jane Doe',
            active: true,
            createdAt: new \DateTimeImmutable('2026-04-07T12:10:00+00:00'),
        );

        $updatedStream = $runtime->join($participant);

        $this->assertCount(2, $updatedStream->participants);
        $this->assertSame($updatedStream, $runtime->getStream());
    }

    public function testMarkReadDelegatesToBackendAndResetsUnreadCount(): void
    {
        $backend = new InMemoryStreamBackend();
        $stream = StreamRuntimeTestStreamFactory::create('stream-1');
        $backend->seedStream($stream, unreadEventCount: 3);

        $runtime = new StreamRuntime($stream, $backend, new InMemoryUserContext());

        $runtime->markRead();

        $this->assertSame(0, $runtime->getUnreadEventCount());
        $this->assertSame(1, $backend->getMarkReadCallCount('stream-1'));
    }

    public function testRefreshReplacesInternalStreamSnapshot(): void
    {
        $backend = new InMemoryStreamBackend();
        $stream = StreamRuntimeTestStreamFactory::create('stream-1');
        $backend->seedStream($stream);

        $runtime = new StreamRuntime($stream, $backend, new InMemoryUserContext());

        $backend->appendEvent(
            new InMemoryUserContext(),
            'stream-1',
            new StreamEvent(
                id: 'event-2',
                streamId: 'stream-1',
                userId: null,
                type: StreamEventType::SYSTEM,
                content: 'Joined',
                createdAt: new \DateTimeImmutable('2026-04-07T12:15:00+00:00'),
            )
        );

        $refreshedStream = $runtime->refresh();

        $this->assertCount(1, $refreshedStream->events);
        $this->assertSame($refreshedStream, $runtime->getStream());
    }
}
