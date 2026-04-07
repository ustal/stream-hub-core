<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Render\WidgetTemplateResolverInterface;

final class InMemoryWidgetTemplateResolver implements WidgetTemplateResolverInterface
{
    public function __construct(private readonly ?string $template = null)
    {
    }

    public function resolve(
        string $widgetClass,
        string $rendererName,
        array $templates,
        StreamContextInterface $context,
    ): ?string {
        return $this->template;
    }
}
