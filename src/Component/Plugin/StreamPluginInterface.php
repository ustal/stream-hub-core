<?php

namespace Ustal\StreamHub\Component\Plugin;

use Ustal\StreamHub\Component\CommandBus\StreamCommandHandlerInterface;
use Ustal\StreamHub\Component\Widget\StreamWidgetInterface;

interface StreamPluginInterface
{
    public static function getName(): string;

    public static function getConfig(): array;

    /**
     * @return array<class-string<StreamCommandHandlerInterface>>
     */
    public static function getCommandHandlers(): array;

    /**
     * @return array<class-string<StreamWidgetInterface>>
     */
    public static function getWidgets(): array;
}
