<?php

namespace Ustal\StreamHub\Core\Command;

use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Guard\StreamCommandGuardInterface;
use Ustal\StreamHub\Core\Exception\CommandDeniedException;

final readonly class GuardedCommandBus implements CommandBusInterface
{
    /**
     * @param iterable<StreamCommandGuardInterface> $guards
     */
    public function __construct(
        private CommandBusInterface $inner,
        private iterable $guards = [],
    ) {}

    public function handle(StreamCommandInterface $command, StreamContextInterface $context): void
    {
        foreach ($this->guards as $guard) {
            if (!$guard->supports($command)) {
                continue;
            }

            $decision = $guard->decide($command, $context);

            if (!$decision->allowed) {
                throw new CommandDeniedException($decision->reason ?? 'Command execution denied by guard.');
            }
        }

        $this->inner->handle($command, $context);
    }
}
