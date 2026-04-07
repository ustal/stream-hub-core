<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\CommandBus\StreamCommandHandlerInterface;
use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;

final class SecondRecordingTestCommandHandler implements StreamCommandHandlerInterface
{
    public function handle(StreamCommandInterface $command, StreamContextInterface $context): void
    {
    }

    public static function supports(): string
    {
        return TestCommand::class;
    }
}
