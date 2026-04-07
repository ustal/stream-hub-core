<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Model\Stream;
use Ustal\StreamHub\Component\Model\StreamCollection;
use Ustal\StreamHub\Component\Model\StreamEvent;
use Ustal\StreamHub\Component\Model\StreamEventCollection;
use Ustal\StreamHub\Component\Model\StreamParticipant;
use Ustal\StreamHub\Component\Storage\StreamBackendInterface;

final class InMemoryStreamBackend implements StreamBackendInterface
{
    /** @var array<string, Stream> */
    private array $streams = [];

    /** @var array<string, int> */
    private array $unreadEventCountByStream = [];

    /** @var array<string, int> */
    private array $markReadCalls = [];

    private int $createStreamCallCount = 0;

    /** @var StreamParticipant[] */
    private array $lastCreatedParticipants = [];

    private ?StreamEvent $lastAppendedEvent = null;

    public function seedStream(Stream $stream, int $unreadEventCount = 0): void
    {
        $this->streams[$stream->id] = $stream;
        $this->unreadEventCountByStream[$stream->id] = $unreadEventCount;
    }

    public function createStream(StreamContextInterface $context, array $participants): Stream
    {
        $this->createStreamCallCount++;
        $this->lastCreatedParticipants = $participants;

        $stream = new Stream(
            id: 'stream-' . (count($this->streams) + 1),
            participants: $participants,
            events: new StreamEventCollection(),
            createdAt: new \DateTimeImmutable(),
            updatedAt: new \DateTimeImmutable(),
        );

        $this->seedStream($stream);

        return $stream;
    }

    public function joinStream(StreamContextInterface $context, string $streamId, StreamParticipant $participant): Stream
    {
        $stream = $this->streams[$streamId];
        $participants = [...$stream->participants, $participant];

        $updated = new Stream(
            id: $stream->id,
            participants: $participants,
            events: $stream->events,
            createdAt: $stream->createdAt,
            updatedAt: new \DateTimeImmutable(),
        );

        $this->streams[$streamId] = $updated;

        return $updated;
    }

    public function getStream(StreamContextInterface $context, string $streamId): ?Stream
    {
        return $this->streams[$streamId] ?? null;
    }

    public function getStreams(StreamContextInterface $context): StreamCollection
    {
        return new StreamCollection(...array_values($this->streams));
    }

    public function appendEvent(StreamContextInterface $context, string $streamId, StreamEvent $event): StreamEvent
    {
        $stream = $this->streams[$streamId];
        $events = iterator_to_array($stream->events->getIterator());
        $events[] = $event;

        $updated = new Stream(
            id: $stream->id,
            participants: $stream->participants,
            events: new StreamEventCollection(...$events),
            createdAt: $stream->createdAt,
            updatedAt: new \DateTimeImmutable(),
        );

        $this->streams[$streamId] = $updated;
        $this->unreadEventCountByStream[$streamId] = ($this->unreadEventCountByStream[$streamId] ?? 0) + 1;
        $this->lastAppendedEvent = $event;

        return $event;
    }

    public function markRead(StreamContextInterface $context, string $streamId): void
    {
        $this->markReadCalls[$streamId] = ($this->markReadCalls[$streamId] ?? 0) + 1;
        $this->unreadEventCountByStream[$streamId] = 0;
    }

    public function getUnreadStreamCount(StreamContextInterface $context): int
    {
        return count(array_filter(
            $this->unreadEventCountByStream,
            static fn (int $count): bool => $count > 0
        ));
    }

    public function getUnreadEventCount(StreamContextInterface $context): int
    {
        return array_sum($this->unreadEventCountByStream);
    }

    public function getUnreadEventCountForStream(StreamContextInterface $context, string $streamId): int
    {
        return $this->unreadEventCountByStream[$streamId] ?? 0;
    }

    public function getMarkReadCallCount(string $streamId): int
    {
        return $this->markReadCalls[$streamId] ?? 0;
    }

    public function getCreateStreamCallCount(): int
    {
        return $this->createStreamCallCount;
    }

    /**
     * @return StreamParticipant[]
     */
    public function getLastCreatedParticipants(): array
    {
        return $this->lastCreatedParticipants;
    }

    public function getLastAppendedEvent(): ?StreamEvent
    {
        return $this->lastAppendedEvent;
    }
}
