<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Enum\DefaultSlotName;
use Ustal\StreamHub\Component\Enum\WidgetPlacementMode;
use Ustal\StreamHub\Component\Render\HtmlRenderResult;
use Ustal\StreamHub\Component\Render\RenderResult;
use Ustal\StreamHub\Component\Widget\AbstractStreamWidget;

class AnotherReplaceMainWidget extends AbstractStreamWidget
{
    public static function getName(): string
    {
        return 'another-replace-main';
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
        return new HtmlRenderResult('<div>Another Replace Main</div>');
    }

    public static function supports(StreamContextInterface $context): bool
    {
        return true;
    }

    public static function getPlacementMode(): WidgetPlacementMode
    {
        return WidgetPlacementMode::REPLACE;
    }
}
