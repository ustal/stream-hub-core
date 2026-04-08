<?php

namespace Ustal\StreamHub\Core;

use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Model\Stream;
use Ustal\StreamHub\Component\Model\StreamCollection;
use Ustal\StreamHub\Component\Storage\StreamBackendInterface;
use Ustal\StreamHub\Core\Command\CommandBusInterface;

final readonly class StreamHub implements StreamHubInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private StreamBackendInterface $backend,
        private StreamContextInterface $context,
    ) {}

    public function dispatch(StreamCommandInterface $command): void
    {
        $this->commandBus->handle($command, $this->context);
    }

    public function getStream(string $streamId): ?Stream
    {
        return $this->backend->getStream($this->context, $streamId);
    }

    public function getStreams(): StreamCollection
    {
        return $this->backend->getStreams($this->context);
    }

    public function getUnreadStreamCount(): int
    {
        return $this->backend->getUnreadStreamCount($this->context);
    }

    public function getUnreadEventCount(): int
    {
        return $this->backend->getUnreadEventCount($this->context);
    }

    public function getUnreadEventCountForStream(string $streamId): int
    {
        return $this->backend->getUnreadEventCountForStream($this->context, $streamId);
    }
}
