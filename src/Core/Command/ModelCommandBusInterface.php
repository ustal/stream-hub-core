<?php

namespace Ustal\StreamHub\Core\Command;

use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;

interface ModelCommandBusInterface
{
    public function handle(StreamCommandInterface $command, StreamContextInterface $context): void;
}
