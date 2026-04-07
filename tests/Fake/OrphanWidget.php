<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Render\HtmlRenderResult;
use Ustal\StreamHub\Component\Render\RenderResult;
use Ustal\StreamHub\Component\Widget\AbstractStreamWidget;

class OrphanWidget extends AbstractStreamWidget
{
    public static function getName(): string
    {
        return 'orphan';
    }

    public static function getSlot(): \BackedEnum
    {
        return TestSlot::ORPHAN;
    }

    public function isVisible(StreamContextInterface $context): bool
    {
        return true;
    }

    public function render(StreamContextInterface $context): RenderResult
    {
        return new HtmlRenderResult('<div>Orphan</div>');
    }

    public static function supports(StreamContextInterface $context): bool
    {
        return true;
    }
}
