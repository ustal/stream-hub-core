<?php

namespace Ustal\StreamHub\Core\Plugins\CoreStream\Command;

use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Model\StreamParticipant;

final readonly class CreateStreamCommand implements StreamCommandInterface
{
    /**
     * @param StreamParticipant[] $participants
     */
    public function __construct(
        public StreamContextInterface $context,
        public array $participants,
    ) {}
}
