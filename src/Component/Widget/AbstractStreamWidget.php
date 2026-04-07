<?php

namespace Ustal\StreamHub\Component\Widget;

use Ustal\StreamHub\Component\Enum\WidgetPlacementMode;

abstract class AbstractStreamWidget implements StreamWidgetInterface
{
    public static function provideSlots(): array
    {
        return [];
    }

    public static function getPlacementMode(): WidgetPlacementMode
    {
        return WidgetPlacementMode::APPEND;
    }
}
