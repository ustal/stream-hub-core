<?php

namespace Ustal\StreamHub\Component\Service;

use Ustal\StreamHub\Component\Enum\WidgetPlacementMode;
use Ustal\StreamHub\Component\Widget\StreamWidgetInterface;

final readonly class WidgetDefinition
{
    /**
     * @param class-string<StreamWidgetInterface> $class
     */
    public function __construct(
        public string $class,
        public string $targetSlot,
        public WidgetPlacementMode $placementMode,
    ) {}
}
