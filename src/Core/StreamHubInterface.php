<?php

namespace Ustal\StreamHub\Core;

use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Model\Stream;
use Ustal\StreamHub\Component\Model\StreamCollection;

interface StreamHubInterface
{
    public function dispatch(StreamCommandInterface $command): void;

    public function getStream(string $streamId): ?Stream;

    public function viewStream(string $streamId): ?Stream;

    public function getStreams(): StreamCollection;

    public function getUnreadStreamCount(): int;

    public function getUnreadEventCount(): int;

    public function getUnreadEventCountForStream(string $streamId): int;
}
