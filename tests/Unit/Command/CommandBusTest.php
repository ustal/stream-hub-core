<?php

namespace Ustal\StreamHub\Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Core\Command\CommandBus;
use Ustal\StreamHub\Tests\Fake\InMemoryUserContext;
use Ustal\StreamHub\Tests\Fake\RecordingTestCommandHandler;
use Ustal\StreamHub\Tests\Fake\SecondRecordingTestCommandHandler;
use Ustal\StreamHub\Tests\Fake\TestCommand;

class CommandBusTest extends TestCase
{
    public function testDispatchesCommandToMatchingHandler(): void
    {
        $handler = new RecordingTestCommandHandler();
        $bus = new CommandBus([$handler]);

        $bus->handle(new TestCommand('hello'), new InMemoryUserContext('user-42'));

        $this->assertSame('hello', $handler->handledPayload);
        $this->assertSame('user-42', $handler->handledUserId);
    }

    public function testThrowsWhenHandlerIsMissing(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No handler for');

        (new CommandBus())->handle(new TestCommand('hello'), new InMemoryUserContext());
    }

    public function testThrowsWhenTwoHandlersSupportSameCommand(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('already has a registered handler');

        new CommandBus([
            new RecordingTestCommandHandler(),
            new SecondRecordingTestCommandHandler(),
        ]);
    }
}
