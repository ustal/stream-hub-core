<?php

namespace Ustal\StreamHub\Core\Plugins\CoreStream\Command;

use Ustal\StreamHub\Component\CommandBus\StreamCommandHandlerInterface;
use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Storage\StreamBackendInterface;

final readonly class CreateStreamCommandHandler implements StreamCommandHandlerInterface
{
    public function __construct(private StreamBackendInterface $backend)
    {
    }

    public function handle(StreamCommandInterface $command, StreamContextInterface $context): void
    {
        if (!$command instanceof CreateStreamCommand) {
            throw new \LogicException('Unexpected command type.');
        }

        $this->backend->createStream($command->context, $command->participants);
    }

    public static function supports(): string
    {
        return CreateStreamCommand::class;
    }
}
