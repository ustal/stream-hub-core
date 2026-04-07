<?php

namespace Ustal\StreamHub\Component\Service;

use Ustal\StreamHub\Component\Enum\WidgetPlacementMode;
use Ustal\StreamHub\Component\ValueObject\LayoutSlot;
use Ustal\StreamHub\Component\Widget\StreamWidgetInterface;

final readonly class SlotAssignment
{
    /**
     * @param class-string<StreamWidgetInterface> $widgetClass
     * @param LayoutSlot[] $providedSlots
     */
    public function __construct(
        public string $pluginId,
        public string $widgetClass,
        public string $targetSlot,
        public WidgetPlacementMode $placementMode,
        public array $providedSlots,
    ) {}
}
