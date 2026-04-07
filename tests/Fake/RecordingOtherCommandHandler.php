<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\CommandBus\StreamCommandHandlerInterface;
use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;

final class RecordingOtherCommandHandler implements StreamCommandHandlerInterface
{
    public bool $handled = false;

    public function handle(StreamCommandInterface $command, StreamContextInterface $context): void
    {
        if (!$command instanceof OtherCommand) {
            throw new \LogicException('Unexpected command type.');
        }

        $this->handled = true;
    }

    public static function supports(): string
    {
        return OtherCommand::class;
    }
}
