<?php

namespace Ustal\StreamHub\Component\Widget;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Enum\WidgetPlacementMode;
use Ustal\StreamHub\Component\Render\RenderResult;
use Ustal\StreamHub\Component\ValueObject\LayoutSlot;

interface StreamWidgetInterface
{
    public static function getSlot(): \BackedEnum;

    public function isVisible(StreamContextInterface $context): bool;

    public function render(StreamContextInterface $context): RenderResult;

    public static function supports(StreamContextInterface $context): bool;

    /**
     * @return LayoutSlot[]
     */
    public static function provideSlots(): array;

    public static function getPlacementMode(): WidgetPlacementMode;

    public static function getName(): string;
}
