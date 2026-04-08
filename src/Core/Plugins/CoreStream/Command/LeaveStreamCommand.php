<?php

namespace Ustal\StreamHub\Core\Plugins\CoreStream\Command;

use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;

final readonly class LeaveStreamCommand implements StreamCommandInterface
{
    public function __construct(
        public StreamContextInterface $context,
        public string $streamId,
        public string $userId,
        public \DateTimeImmutable $leftAt,
    ) {}
}
