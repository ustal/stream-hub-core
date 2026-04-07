<?php

namespace Ustal\StreamHub\Core\Plugins\CoreStream\Command;

use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;

final readonly class MarkStreamReadCommand implements StreamCommandInterface
{
    public function __construct(
        public StreamContextInterface $context,
        public string $streamId,
    ) {}
}
