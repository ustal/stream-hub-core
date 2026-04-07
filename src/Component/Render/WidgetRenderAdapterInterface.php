<?php

namespace Ustal\StreamHub\Component\Render;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Core\StreamRuntime;

interface WidgetRenderAdapterInterface
{
    public function render(string $widgetClass, StreamRuntime $runtime, StreamContextInterface $context): RenderResult;
}
