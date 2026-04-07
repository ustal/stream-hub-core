<?php

namespace Ustal\StreamHub\Tests\Unit\Widget;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Exception\TransformationException;
use Ustal\StreamHub\Component\Render\ViewRendererInterface;
use Ustal\StreamHub\Component\Render\WidgetTemplateResolverInterface;
use Ustal\StreamHub\Tests\Fake\InMemoryWidgetTemplateResolver;
use Ustal\StreamHub\Tests\Fake\InMemoryUserContext;
use Ustal\StreamHub\Tests\Fake\InMemoryViewRenderer;
use Ustal\StreamHub\Tests\Fake\TemplatedWidget;

final class AbstractStreamWidgetTest extends TestCase
{
    public function testRenderTemplateUsesRendererFromContextAndTemplateMap(): void
    {
        $renderer = new InMemoryViewRenderer('twig', '<div>Rendered</div>');
        $context = new InMemoryUserContext(values: [
            ViewRendererInterface::class => $renderer,
        ]);

        $result = (new TemplatedWidget())->render($context);

        $this->assertSame('<div>Rendered</div>', $result->html);
        $this->assertSame('widget/templated.html.twig', $renderer->lastTemplate);
        $this->assertSame(['message' => 'hello'], $renderer->lastContext);
    }

    public function testRenderTemplateFailsWhenRendererIsMissing(): void
    {
        $this->expectException(TransformationException::class);
        $this->expectExceptionMessage('requires');

        (new TemplatedWidget())->render(new InMemoryUserContext());
    }

    public function testRenderTemplateFailsWhenRendererTemplateIsMissing(): void
    {
        $this->expectException(TransformationException::class);
        $this->expectExceptionMessage('does not define a template');

        $context = new InMemoryUserContext(values: [
            ViewRendererInterface::class => new InMemoryViewRenderer('html'),
        ]);

        (new TemplatedWidget())->render($context);
    }

    public function testRenderTemplateAllowsResolverToOverrideTemplate(): void
    {
        $renderer = new InMemoryViewRenderer('twig', '<div>Override</div>');
        $context = new InMemoryUserContext(values: [
            ViewRendererInterface::class => $renderer,
            WidgetTemplateResolverInterface::class => new InMemoryWidgetTemplateResolver('widget/override.html.twig'),
        ]);

        $result = (new TemplatedWidget())->render($context);

        $this->assertSame('<div>Override</div>', $result->html);
        $this->assertSame('widget/override.html.twig', $renderer->lastTemplate);
    }
}
