<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\CommandBus\StreamCommandHandlerInterface;
use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;

final class RecordingTestCommandHandler implements StreamCommandHandlerInterface
{
    public ?string $handledPayload = null;
    public ?string $handledUserId = null;

    public function handle(StreamCommandInterface $command, StreamContextInterface $context): void
    {
        if (!$command instanceof TestCommand) {
            throw new \LogicException('Unexpected command type.');
        }

        $this->handledPayload = $command->payload;
        $this->handledUserId = $context->getUserId();
    }

    public static function supports(): string
    {
        return TestCommand::class;
    }
}
