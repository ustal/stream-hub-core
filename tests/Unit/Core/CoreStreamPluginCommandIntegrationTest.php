<?php

namespace Ustal\StreamHub\Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Enum\StreamEventType;
use Ustal\StreamHub\Component\Model\Stream;
use Ustal\StreamHub\Component\Model\StreamEvent;
use Ustal\StreamHub\Component\Model\StreamEventCollection;
use Ustal\StreamHub\Component\Model\StreamParticipant;
use Ustal\StreamHub\Core\Command\CommandBus;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\AppendStreamEventCommand;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\AppendStreamEventCommandHandler;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\CreateStreamCommand;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\CreateStreamCommandHandler;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\LeaveStreamCommand;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\LeaveStreamCommandHandler;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\MarkStreamReadCommand;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\MarkStreamReadCommandHandler;
use Ustal\StreamHub\Tests\Fake\InMemoryStreamBackend;
use Ustal\StreamHub\Tests\Fake\InMemoryUserContext;

class CoreStreamPluginCommandIntegrationTest extends TestCase
{
    public function testCoreLowLevelHandlersHandleCreateAppendAndMarkReadCommands(): void
    {
        $backend = new InMemoryStreamBackend();
        $backend->seedStream($this->createStream('stream-1'), unreadEventCount: 2);
        $bus = new CommandBus([
            new CreateStreamCommandHandler($backend),
            new AppendStreamEventCommandHandler($backend),
            new LeaveStreamCommandHandler($backend),
            new MarkStreamReadCommandHandler($backend),
        ]);

        $participant = new StreamParticipant(
            userId: 'user-1',
            displayName: 'John Smith',
            active: true,
            createdAt: new \DateTimeImmutable('2026-04-07T13:00:00+00:00'),
        );

        $bus->handle(
            new CreateStreamCommand(
                context: new InMemoryUserContext(),
                streamId: 'stream-created',
                participants: [$participant],
            ),
            new InMemoryUserContext()
        );

        $event = new StreamEvent(
            id: 'event-1',
            streamId: 'stream-1',
            userId: 'user-1',
            type: StreamEventType::MESSAGE,
            content: 'Hello from core command',
            createdAt: new \DateTimeImmutable('2026-04-07T13:05:00+00:00'),
        );

        $bus->handle(
            new AppendStreamEventCommand(
                context: new InMemoryUserContext(),
                streamId: 'stream-1',
                event: $event,
            ),
            new InMemoryUserContext()
        );

        $bus->handle(
            new LeaveStreamCommand(
                context: new InMemoryUserContext(),
                streamId: 'stream-created',
                userId: 'user-1',
                leftAt: new \DateTimeImmutable('2026-04-07T13:10:00+00:00'),
            ),
            new InMemoryUserContext()
        );

        $bus->handle(
            new MarkStreamReadCommand(
                context: new InMemoryUserContext(),
                streamId: 'stream-1',
            ),
            new InMemoryUserContext()
        );

        $this->assertSame(1, $backend->getCreateStreamCallCount());
        $this->assertSame([$participant], $backend->getLastCreatedParticipants());
        $createdStream = $backend->getStream(new InMemoryUserContext(), 'stream-created');
        $this->assertNotNull($createdStream);
        $this->assertFalse($createdStream->participants[0]->active);
        $this->assertNotNull($createdStream->participants[0]->leftAt);
        $this->assertSame($event, $backend->getLastAppendedEvent());
        $this->assertSame(1, $backend->getMarkReadCallCount('stream-1'));
        $this->assertSame(0, $backend->getUnreadEventCountForStream(new InMemoryUserContext(), 'stream-1'));
    }

    private function createStream(string $id): Stream
    {
        return new Stream(
            id: $id,
            participants: [],
            events: new StreamEventCollection(),
            createdAt: new \DateTimeImmutable('2026-04-07T12:00:00+00:00'),
            updatedAt: new \DateTimeImmutable('2026-04-07T12:00:00+00:00'),
        );
    }
}
