<?php

namespace Ustal\StreamHub\Core\Plugins\CoreStream\Command;

use Ustal\StreamHub\Component\CommandBus\StreamCommandHandlerInterface;
use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Storage\StreamBackendInterface;

final readonly class JoinStreamCommandHandler implements StreamCommandHandlerInterface
{
    public function __construct(private StreamBackendInterface $backend)
    {
    }

    public function handle(StreamCommandInterface $command, StreamContextInterface $context): void
    {
        if (!$command instanceof JoinStreamCommand) {
            throw new \LogicException('Unexpected command type.');
        }

        $this->backend->joinStream($command->context, $command->streamId, $command->participant);
    }

    public static function supports(): string
    {
        return JoinStreamCommand::class;
    }
}
