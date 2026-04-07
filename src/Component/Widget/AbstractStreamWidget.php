<?php

namespace Ustal\StreamHub\Component\Widget;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Enum\WidgetPlacementMode;
use Ustal\StreamHub\Component\Exception\TransformationException;
use Ustal\StreamHub\Component\Render\HtmlRenderResult;
use Ustal\StreamHub\Component\Render\ViewRendererInterface;
use Ustal\StreamHub\Component\Render\WidgetTemplateResolverInterface;

abstract class AbstractStreamWidget implements StreamWidgetInterface
{
    public static function provideSlots(): array
    {
        return [];
    }

    public static function getPlacementMode(): WidgetPlacementMode
    {
        return WidgetPlacementMode::APPEND;
    }

    /**
     * @return array<string, string>
     */
    public static function getTemplates(): array
    {
        return [];
    }

    protected function renderTemplate(StreamContextInterface $context, array $templateContext = []): HtmlRenderResult
    {
        $renderer = $context->get(ViewRendererInterface::class);

        if (!$renderer instanceof ViewRendererInterface) {
            throw new TransformationException(sprintf(
                'Widget %s requires %s in stream context.',
                static::class,
                ViewRendererInterface::class
            ));
        }

        $templateResolver = $context->get(WidgetTemplateResolverInterface::class);
        $templates = static::getTemplates();
        $template = $templateResolver instanceof WidgetTemplateResolverInterface
            ? $templateResolver->resolve(static::class, $renderer->getName(), $templates, $context)
            : null;

        $template ??= $templates[$renderer->getName()] ?? null;

        if ($template === null) {
            throw new TransformationException(sprintf(
                'Widget %s does not define a template for renderer "%s".',
                static::class,
                $renderer->getName()
            ));
        }

        return new HtmlRenderResult($renderer->render($template, $templateContext));
    }
}
