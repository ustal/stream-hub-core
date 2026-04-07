<?php

namespace Ustal\StreamHub\Core\Plugins\CoreStream\Command;

use Ustal\StreamHub\Component\CommandBus\StreamCommandHandlerInterface;
use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Storage\StreamBackendInterface;

final readonly class MarkStreamReadCommandHandler implements StreamCommandHandlerInterface
{
    public function __construct(private StreamBackendInterface $backend)
    {
    }

    public function handle(StreamCommandInterface $command, StreamContextInterface $context): void
    {
        if (!$command instanceof MarkStreamReadCommand) {
            throw new \LogicException('Unexpected command type.');
        }

        $this->backend->markRead($command->context, $command->streamId);
    }

    public static function supports(): string
    {
        return MarkStreamReadCommand::class;
    }
}
