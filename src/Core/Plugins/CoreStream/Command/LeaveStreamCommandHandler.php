<?php

namespace Ustal\StreamHub\Core\Plugins\CoreStream\Command;

use Ustal\StreamHub\Component\CommandBus\StreamCommandHandlerInterface;
use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Storage\StreamBackendInterface;

final readonly class LeaveStreamCommandHandler implements StreamCommandHandlerInterface
{
    public function __construct(private StreamBackendInterface $backend)
    {
    }

    public function handle(StreamCommandInterface $command, StreamContextInterface $context): void
    {
        if (!$command instanceof LeaveStreamCommand) {
            throw new \LogicException('Unexpected command type.');
        }

        $this->backend->leaveStream($command->context, $command->streamId, $command->userId, $command->leftAt);
    }

    public static function supports(): string
    {
        return LeaveStreamCommand::class;
    }
}
