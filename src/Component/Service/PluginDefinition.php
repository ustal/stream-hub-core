<?php

namespace Ustal\StreamHub\Component\Service;

use Ustal\StreamHub\Component\Plugin\StreamPluginInterface;
use Ustal\StreamHub\Component\Widget\StreamWidgetInterface;
use Ustal\StreamHub\Component\CommandBus\StreamCommandHandlerInterface;

final readonly class PluginDefinition
{
    /**
     * @param class-string<StreamPluginInterface> $class
     * @param array<class-string<StreamCommandHandlerInterface>> $handlerClasses
     * @param array<class-string<StreamWidgetInterface>> $widgetClasses
     */
    public function __construct(
        public string $id,
        public string $class,
        public array $handlerClasses,
        public array $widgetClasses,
        public bool $isDefault = false,
    ) {}
}
