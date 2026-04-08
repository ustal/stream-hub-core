<?php

namespace Ustal\StreamHub\Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Model\Stream;
use Ustal\StreamHub\Component\Model\StreamEventCollection;
use Ustal\StreamHub\Component\Model\StreamParticipant;
use Ustal\StreamHub\Core\Command\CommandBus;
use Ustal\StreamHub\Core\StreamHub;
use Ustal\StreamHub\Tests\Fake\InMemoryStreamBackend;
use Ustal\StreamHub\Tests\Fake\InMemoryUserContext;
use Ustal\StreamHub\Tests\Fake\RecordingTestCommandHandler;
use Ustal\StreamHub\Tests\Fake\TestCommand;

final class StreamHubTest extends TestCase
{
    public function testItDispatchesCommandsThroughFeatureBus(): void
    {
        $handler = new RecordingTestCommandHandler();
        $backend = new InMemoryStreamBackend();
        $context = new InMemoryUserContext('user-42');
        $streamHub = new StreamHub(new CommandBus([$handler]), $backend, $context);

        $streamHub->dispatch(new TestCommand('payload'));

        self::assertSame('payload', $handler->handledPayload);
        self::assertSame('user-42', $handler->handledUserId);
    }

    public function testItDelegatesReadOperationsToBackendUsingInjectedContext(): void
    {
        $backend = new InMemoryStreamBackend();
        $context = new InMemoryUserContext();
        $stream = new Stream(
            id: 'stream-1',
            participants: [
                new StreamParticipant(
                    userId: 'user-1',
                    displayName: 'User 1',
                    active: true,
                    createdAt: new \DateTimeImmutable('-1 hour'),
                ),
            ],
            events: new StreamEventCollection(),
            createdAt: new \DateTimeImmutable('-1 hour'),
            updatedAt: new \DateTimeImmutable(),
        );
        $backend->seedStream($stream, 3);

        $streamHub = new StreamHub(new CommandBus([]), $backend, $context);

        self::assertSame($stream, $streamHub->getStream('stream-1'));
        self::assertCount(1, $streamHub->getStreams());
        self::assertSame(1, $streamHub->getUnreadStreamCount());
        self::assertSame(3, $streamHub->getUnreadEventCount());
        self::assertSame(3, $streamHub->getUnreadEventCountForStream('stream-1'));
    }
}
