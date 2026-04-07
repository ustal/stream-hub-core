<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Render\HtmlRenderResult;
use Ustal\StreamHub\Component\Render\RenderResult;
use Ustal\StreamHub\Component\Widget\AbstractStreamWidget;

class DetailsWidget extends AbstractStreamWidget
{
    public static function getName(): string
    {
        return 'details';
    }

    public static function getSlot(): \BackedEnum
    {
        return TestSlot::DETAILS;
    }

    public function isVisible(StreamContextInterface $context): bool
    {
        return true;
    }

    public function render(StreamContextInterface $context): RenderResult
    {
        return new HtmlRenderResult('<div>Details</div>');
    }

    public static function supports(StreamContextInterface $context): bool
    {
        return true;
    }
}
