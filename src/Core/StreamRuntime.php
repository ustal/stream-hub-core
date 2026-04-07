<?php

namespace Ustal\StreamHub\Core;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Model\Stream;
use Ustal\StreamHub\Component\Model\StreamEvent;
use Ustal\StreamHub\Component\Model\StreamEventCollection;
use Ustal\StreamHub\Component\Model\StreamParticipant;
use Ustal\StreamHub\Component\Storage\StreamBackendInterface;

class StreamRuntime
{
    public function __construct(
        private Stream $stream,
        private StreamBackendInterface $backend,
        private StreamContextInterface $context,
    ) {}

    public function getStream(): Stream
    {
        return $this->stream;
    }

    public function getEvents(): StreamEventCollection
    {
        return $this->stream->events;
    }

    public function appendEvent(StreamEvent $streamEvent): StreamEvent
    {
        $event = $this->backend->appendEvent($this->context, $this->stream->id, $streamEvent);
        $this->refresh();

        return $event;
    }

    public function join(StreamParticipant $participant): Stream
    {
        $this->stream = $this->backend->joinStream($this->context, $this->stream->id, $participant);

        return $this->stream;
    }

    public function markRead(): void
    {
        $this->backend->markRead($this->context, $this->stream->id);
    }

    public function getUnreadEventCount(): int
    {
        return $this->backend->getUnreadEventCountForStream($this->context, $this->stream->id);
    }

    public function refresh(): Stream
    {
        $stream = $this->backend->getStream($this->context, $this->stream->id);

        if ($stream !== null) {
            $this->stream = $stream;
        }

        return $this->stream;
    }
}
