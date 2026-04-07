<?php

namespace Ustal\StreamHub\Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Enum\StreamEventType;
use Ustal\StreamHub\Component\Model\Stream;
use Ustal\StreamHub\Component\Model\StreamEvent;
use Ustal\StreamHub\Component\Model\StreamEventCollection;
use Ustal\StreamHub\Component\Model\StreamParticipant;
use Ustal\StreamHub\Component\Service\PluginDefinitionBuilder;
use Ustal\StreamHub\Core\Command\CommandBusFactory;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\AppendStreamEventCommand;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\AppendStreamEventCommandHandler;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\CreateStreamCommand;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\CreateStreamCommandHandler;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\MarkStreamReadCommand;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\MarkStreamReadCommandHandler;
use Ustal\StreamHub\Tests\Fake\InMemoryStreamBackend;
use Ustal\StreamHub\Tests\Fake\InMemoryUserContext;

class CoreStreamPluginCommandIntegrationTest extends TestCase
{
    public function testCorePluginIsEnabledByDefaultAndHandlesCreateAppendAndMarkReadCommands(): void
    {
        $backend = new InMemoryStreamBackend();
        $backend->seedStream($this->createStream('stream-1'), unreadEventCount: 2);
        $registry = (new PluginDefinitionBuilder())->build([]);

        $bus = (new CommandBusFactory())->create($registry, [
            new CreateStreamCommandHandler($backend),
            new AppendStreamEventCommandHandler($backend),
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
            new MarkStreamReadCommand(
                context: new InMemoryUserContext(),
                streamId: 'stream-1',
            ),
            new InMemoryUserContext()
        );

        $this->assertSame(1, $backend->getCreateStreamCallCount());
        $this->assertSame([$participant], $backend->getLastCreatedParticipants());
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
