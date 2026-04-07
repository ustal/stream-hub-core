<?php

namespace Ustal\StreamHub\Component\Widget;

class WidgetRegistry
{
    /** @var array<string, array<class-string<StreamWidgetInterface>>> */
    private array $widgetsBySlot = [];

    public function __construct(iterable $widgets)
    {
        foreach ($widgets as $widget) {
            /** @var class-string<StreamWidgetInterface> $widget */
            $this->widgetsBySlot[$widget::getSlot()->value][] = $widget;
        }
    }

    /**
     * @return array<class-string<StreamWidgetInterface>>
     */
    public function getBySlot(\BackedEnum $slot): array
    {
        return $this->widgetsBySlot[$slot->value] ?? [];
    }
}
