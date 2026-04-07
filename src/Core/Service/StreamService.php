<?php

namespace Ustal\StreamHub\Core\Service;

use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Storage\StreamBackendInterface;
use Ustal\StreamHub\Core\Command\CommandBusInterface;
use Ustal\StreamHub\Core\Exception\StreamNotFoundException;
use Ustal\StreamHub\Core\StreamRuntime;

class StreamService
{
    public ?StreamRuntime $currentStream = null;

    public function __construct(
        private StreamBackendInterface $backend,
        private StreamContextInterface $context,
        private CommandBusInterface $bus,
    ) {}

    public function openStream(string $id): StreamRuntime
    {
        $stream = $this->backend->getStream($this->context, $id);

        if (!$stream) {
            throw new StreamNotFoundException();
        }

        return new StreamRuntime(
            $stream,
            $this->backend,
            $this->context,
        );
    }

    public function handle(StreamCommandInterface $command): void
    {
        $this->bus->handle($command, $this->context);
    }

    public function getUnreadCount(): int
    {
        return $this->backend->getUnreadStreamCount($this->context);
    }
}
