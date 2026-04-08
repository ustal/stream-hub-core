<?php

namespace Ustal\StreamHub\Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Core\Command\CommandBus;
use Ustal\StreamHub\Core\Command\GuardedCommandBus;
use Ustal\StreamHub\Core\Exception\CommandDeniedException;
use Ustal\StreamHub\Tests\Fake\AllowAllTestCommandGuard;
use Ustal\StreamHub\Tests\Fake\DenyAllTestCommandGuard;
use Ustal\StreamHub\Tests\Fake\InMemoryUserContext;
use Ustal\StreamHub\Tests\Fake\OtherCommandGuard;
use Ustal\StreamHub\Tests\Fake\RecordingTestCommandHandler;
use Ustal\StreamHub\Tests\Fake\TestCommand;

final class GuardedCommandBusTest extends TestCase
{
    public function testItDispatchesCommandWhenApplicableGuardsAllow(): void
    {
        $handler = new RecordingTestCommandHandler();
        $bus = new GuardedCommandBus(
            new CommandBus([$handler]),
            [new AllowAllTestCommandGuard()]
        );

        $bus->handle(new TestCommand('hello'), new InMemoryUserContext('user-1'));

        self::assertSame('hello', $handler->handledPayload);
        self::assertSame('user-1', $handler->handledUserId);
    }

    public function testItDeniesCommandWhenApplicableGuardDenies(): void
    {
        $handler = new RecordingTestCommandHandler();
        $bus = new GuardedCommandBus(
            new CommandBus([$handler]),
            [new DenyAllTestCommandGuard()]
        );

        $this->expectException(CommandDeniedException::class);
        $this->expectExceptionMessage('not allowed');

        $bus->handle(new TestCommand('hello'), new InMemoryUserContext());
    }

    public function testItIgnoresGuardsThatDoNotSupportCommand(): void
    {
        $handler = new RecordingTestCommandHandler();
        $bus = new GuardedCommandBus(
            new CommandBus([$handler]),
            [new OtherCommandGuard()]
        );

        $bus->handle(new TestCommand('hello'), new InMemoryUserContext('user-7'));

        self::assertSame('hello', $handler->handledPayload);
        self::assertSame('user-7', $handler->handledUserId);
    }
}
