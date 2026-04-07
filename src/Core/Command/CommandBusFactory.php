<?php

namespace Ustal\StreamHub\Core\Command;

use Ustal\StreamHub\Component\CommandBus\StreamCommandHandlerInterface;
use Ustal\StreamHub\Component\Exception\PluginConfigurationException;
use Ustal\StreamHub\Component\Service\PluginDefinitionRegistry;

final class CommandBusFactory
{
    /**
     * @param iterable<StreamCommandHandlerInterface> $handlers
     */
    public function create(PluginDefinitionRegistry $registry, iterable $handlers): CommandBus
    {
        $enabledHandlerClasses = [];

        foreach ($registry->all() as $definition) {
            foreach ($definition->handlerClasses as $handlerClass) {
                $enabledHandlerClasses[$handlerClass] = true;
            }
        }

        $resolvedHandlers = [];

        foreach ($handlers as $handler) {
            if (!isset($enabledHandlerClasses[$handler::class])) {
                throw new PluginConfigurationException(sprintf(
                    'Handler %s is not declared by any enabled plugin.',
                    $handler::class
                ));
            }

            $resolvedHandlers[] = $handler;
        }

        return new CommandBus($resolvedHandlers);
    }
}
