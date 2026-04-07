<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Plugin\AbstractStreamPlugin;

class OrphanWidgetPlugin extends AbstractStreamPlugin
{
    public const NAME = 'orphan-widget';

    public static function getWidgets(): array
    {
        return [
            OrphanWidget::class,
        ];
    }
}
