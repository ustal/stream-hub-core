<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Render\RenderResult;
use Ustal\StreamHub\Component\Render\WidgetRenderAdapterInterface;
use Ustal\StreamHub\Core\StreamRuntime;

final class InMemoryWidgetRenderAdapter implements WidgetRenderAdapterInterface
{
    public function render(string $widgetClass, StreamRuntime $runtime, StreamContextInterface $context): RenderResult
    {
        return (new $widgetClass())->render($context);
    }
}
