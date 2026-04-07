<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Plugin\AbstractStreamPlugin;

class CyclicSlotPlugin extends AbstractStreamPlugin
{
    public const NAME = 'cyclic-slot';

    public static function getWidgets(): array
    {
        return [
            CycleEntryWidget::class,
            CycleBackWidget::class,
        ];
    }
}
