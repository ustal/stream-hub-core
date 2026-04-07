<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Enum\DefaultSlotName;
use Ustal\StreamHub\Component\Enum\SlotAcceptanceMode;
use Ustal\StreamHub\Component\Render\HtmlRenderResult;
use Ustal\StreamHub\Component\Render\RenderResult;
use Ustal\StreamHub\Component\ValueObject\LayoutSlot;
use Ustal\StreamHub\Component\Widget\AbstractStreamWidget;

class CycleEntryWidget extends AbstractStreamWidget
{
    public static function getName(): string
    {
        return 'cycle-entry';
    }

    public static function getSlot(): \BackedEnum
    {
        return DefaultSlotName::MAIN;
    }

    public function isVisible(StreamContextInterface $context): bool
    {
        return true;
    }

    public function render(StreamContextInterface $context): RenderResult
    {
        return new HtmlRenderResult('<div>Cycle Entry</div>');
    }

    public static function supports(StreamContextInterface $context): bool
    {
        return true;
    }

    public static function provideSlots(): array
    {
        return [
            new LayoutSlot(TestSlot::DETAILS, SlotAcceptanceMode::ANY),
        ];
    }
}
