<?php

namespace Ustal\StreamHub\Component\Render;

use Ustal\StreamHub\Component\Context\StreamContextInterface;

interface WidgetTemplateResolverInterface
{
    /**
     * @param array<string, string> $templates
     */
    public function resolve(
        string $widgetClass,
        string $rendererName,
        array $templates,
        StreamContextInterface $context,
    ): ?string;
}
