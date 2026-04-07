<?php

namespace Ustal\StreamHub\Component\Plugin;

abstract class AbstractStreamPlugin implements StreamPluginInterface
{
    final public static function getName(): string
    {
        if (!defined('static::NAME')) {
            throw new \LogicException(
                sprintf('Plugin %s must define NAME constant', static::class)
            );
        }

        return static::NAME;
    }

    public static function getConfig(): array
    {
        return [];
    }

    public static function getCommandHandlers(): array
    {
        return [];
    }

    public static function getWidgets(): array
    {
        return [];
    }
}
