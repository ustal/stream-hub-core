<?php

namespace Ustal\StreamHub\Component\Guard;

use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;

interface StreamCommandGuardInterface
{
    public function supports(StreamCommandInterface $command): bool;

    public function decide(StreamCommandInterface $command, StreamContextInterface $context): GuardDecision;
}
