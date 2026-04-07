<?php

namespace Ustal\StreamHub\Component\Render;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Model\Stream;

interface WidgetRenderAdapterInterface
{
    public function render(string $widgetClass, Stream $stream, StreamContextInterface $context): RenderResult;
}
