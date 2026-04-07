<?php

namespace Ustal\StreamHub\Core\Render;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Enum\DefaultSlotName;
use Ustal\StreamHub\Core\StreamRuntime;

final class StreamPageRenderer implements StreamPageRendererInterface
{
    public function __construct(private SlotRendererInterface $slotRenderer)
    {
    }

    public function render(StreamRuntime $runtime, StreamContextInterface $context): string
    {
        return sprintf(
            '<div class="stream-hub">%s</div>',
            $this->slotRenderer->render(DefaultSlotName::MAIN, $runtime, $context)
        );
    }
}
