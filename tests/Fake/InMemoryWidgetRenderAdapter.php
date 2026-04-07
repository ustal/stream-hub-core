<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Model\Stream;
use Ustal\StreamHub\Component\Render\RenderResult;
use Ustal\StreamHub\Component\Render\WidgetRenderAdapterInterface;

final class InMemoryWidgetRenderAdapter implements WidgetRenderAdapterInterface
{
    public function render(string $widgetClass, Stream $stream, StreamContextInterface $context): RenderResult
    {
        return (new $widgetClass())->render($context);
    }
}
