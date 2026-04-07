<?php

namespace Ustal\StreamHub\Component\Storage;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Model\Stream;
use Ustal\StreamHub\Component\Model\StreamCollection;
use Ustal\StreamHub\Component\Model\StreamEvent;
use Ustal\StreamHub\Component\Model\StreamParticipant;

interface StreamBackendInterface
{
    /**
     * @param StreamParticipant[] $participants
     */
    public function createStream(StreamContextInterface $context, array $participants): Stream;

    public function joinStream(StreamContextInterface $context, string $streamId, StreamParticipant $participant): Stream;

    public function getStream(StreamContextInterface $context, string $streamId): ?Stream;

    public function getStreams(StreamContextInterface $context): StreamCollection;

    public function appendEvent(StreamContextInterface $context, string $streamId, StreamEvent $event): StreamEvent;

    public function markRead(StreamContextInterface $context, string $streamId): void;

    public function getUnreadStreamCount(StreamContextInterface $context): int;

    public function getUnreadEventCount(StreamContextInterface $context): int;

    public function getUnreadEventCountForStream(StreamContextInterface $context, string $streamId): int;
}
