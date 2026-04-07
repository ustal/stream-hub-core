<?php

namespace Ustal\StreamHub\Core\Plugins\CoreStream\Command;

use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Model\StreamParticipant;

final readonly class JoinStreamCommand implements StreamCommandInterface
{
    public function __construct(
        public StreamContextInterface $context,
        public string $streamId,
        public StreamParticipant $participant,
    ) {}
}
