<?php

namespace Ustal\StreamHub\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Model\StreamParticipant;

class StreamParticipantTest extends TestCase
{
    public function testItStoresParticipantState(): void
    {
        $createdAt = new \DateTimeImmutable('2026-04-07T11:00:00+00:00');
        $leftAt = new \DateTimeImmutable('2026-04-07T12:00:00+00:00');
        $lastReadAt = new \DateTimeImmutable('2026-04-07T11:30:00+00:00');

        $participant = new StreamParticipant(
            userId: 'user-1',
            displayName: 'John Smith',
            settings: ['muted' => true],
            active: false,
            createdAt: $createdAt,
            leftAt: $leftAt,
            lastReadAt: $lastReadAt,
        );

        $this->assertSame('user-1', $participant->userId);
        $this->assertSame('John Smith', $participant->displayName);
        $this->assertSame(['muted' => true], $participant->settings);
        $this->assertFalse($participant->active);
        $this->assertSame($createdAt, $participant->createdAt);
        $this->assertSame($leftAt, $participant->leftAt);
        $this->assertSame($lastReadAt, $participant->lastReadAt);
    }
}
