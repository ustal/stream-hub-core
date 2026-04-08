<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Guard\GuardDecision;
use Ustal\StreamHub\Component\Guard\StreamCommandGuardInterface;

final class OtherCommandGuard implements StreamCommandGuardInterface
{
    public function supports(StreamCommandInterface $command): bool
    {
        return $command instanceof OtherCommand;
    }

    public function decide(StreamCommandInterface $command, StreamContextInterface $context): GuardDecision
    {
        return GuardDecision::deny('other command denied');
    }
}
