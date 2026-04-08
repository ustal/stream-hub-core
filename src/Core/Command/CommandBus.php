<?php

namespace Ustal\StreamHub\Core\Command;

use Ustal\StreamHub\Component\CommandBus\StreamCommandHandlerInterface;
use Ustal\StreamHub\Component\CommandBus\StreamCommandInterface;
use Ustal\StreamHub\Component\Context\StreamContextInterface;

class CommandBus implements CommandBusInterface, ModelCommandBusInterface
{
    /** @var array<class-string<StreamCommandInterface>, StreamCommandHandlerInterface> */
    private array $map = [];

    public function __construct(iterable $handlers = [])
    {
        foreach ($handlers as $handler) {
            $commandClass = $handler::supports();

            if (isset($this->map[$commandClass])) {
                throw new \LogicException(sprintf(
                    'Command %s already has a registered handler.',
                    $commandClass
                ));
            }

            $this->map[$commandClass] = $handler;
        }
    }

    public function handle(StreamCommandInterface $command, StreamContextInterface $context): void
    {
        $class = $command::class;

        if (!isset($this->map[$class])) {
            throw new \RuntimeException("No handler for $class");
        }

        $this->map[$class]->handle($command, $context);
    }
}
