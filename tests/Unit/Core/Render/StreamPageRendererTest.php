<?php

namespace Ustal\StreamHub\Tests\Unit\Core\Render;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Enum\DefaultSlotName;
use Ustal\StreamHub\Component\Service\PluginDefinitionBuilder;
use Ustal\StreamHub\Component\Service\SlotTreeBuilder;
use Ustal\StreamHub\Core\Render\Renderer;
use Ustal\StreamHub\Core\Render\SlotRenderer;
use Ustal\StreamHub\Core\Render\StreamPageRenderer;
use Ustal\StreamHub\Core\StreamRuntime;
use Ustal\StreamHub\Tests\Fake\DefinitionTestPlugin;
use Ustal\StreamHub\Tests\Fake\HtmlRenderHandler;
use Ustal\StreamHub\Tests\Fake\InMemoryStreamBackend;
use Ustal\StreamHub\Tests\Fake\InMemoryUserContext;
use Ustal\StreamHub\Tests\Fake\InMemoryWidgetRenderAdapter;
use Ustal\StreamHub\Tests\Unit\Core\StreamRuntimeTestStreamFactory;

class StreamPageRendererTest extends TestCase
{
    public function testItWrapsMainSlotOutputIntoRootContainer(): void
    {
        $registry = (new PluginDefinitionBuilder())->build(
            [DefinitionTestPlugin::class],
            [DefaultSlotName::MAIN]
        );
        $slotTree = (new SlotTreeBuilder())->build($registry, [DefaultSlotName::MAIN]);
        $slotRenderer = new SlotRenderer(
            $slotTree,
            new Renderer([new HtmlRenderHandler()]),
            new InMemoryWidgetRenderAdapter(),
        );
        $pageRenderer = new StreamPageRenderer($slotRenderer);

        $runtime = new StreamRuntime(
            StreamRuntimeTestStreamFactory::create('stream-1'),
            new InMemoryStreamBackend(),
            new InMemoryUserContext(),
        );

        $html = $pageRenderer->render($runtime, new InMemoryUserContext());

        $this->assertSame(
            '<div class="stream-hub"><div>Main</div></div>',
            $html
        );
    }
}
