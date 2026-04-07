<?php

namespace Ustal\StreamHub\Core\Render;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Render\WidgetRenderAdapterInterface;
use Ustal\StreamHub\Component\Service\SlotTree;
use Ustal\StreamHub\Core\StreamRuntime;

final class SlotRenderer implements SlotRendererInterface
{
    public function __construct(
        private SlotTree $slotTree,
        private Renderer $renderer,
        private WidgetRenderAdapterInterface $widgetRenderAdapter,
    ) {}

    public function render(\BackedEnum $slot, StreamRuntime $runtime, StreamContextInterface $context): string
    {
        $html = '';

        foreach ($this->slotTree->getAssignmentsForSlot($slot) as $assignment) {
            if (!$assignment->widgetClass::supports($context)) {
                continue;
            }

            $html .= $this->renderer->render(
                $this->widgetRenderAdapter->render($assignment->widgetClass, $runtime->getStream(), $context)
            );
        }

        return $html;
    }
}
