<?php

namespace Ustal\StreamHub\Tests\Unit\Plugin;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Plugin\AbstractStreamPlugin;
use Ustal\StreamHub\Tests\Fake\TestPlugin;

class AbstractStreamPluginTest extends TestCase
{
    public function testGetNameReturnsConstant(): void
    {
        $this->assertSame('my-plugin_name', TestPlugin::getName());
    }

    public function testDefaultConfigIsEmpty(): void
    {
        $this->assertSame([], TestPlugin::getConfig());
    }

    public function testDefaultWidgetsAreEmpty(): void
    {
        $this->assertSame([], TestPlugin::getWidgets());
    }

    public function testDefaultCommandHandlersAreEmpty(): void
    {
        $this->assertSame([], TestPlugin::getCommandHandlers());
    }

    public function testThrowsExceptionIfNameNotDefined(): void
    {
        $plugin = new class extends AbstractStreamPlugin {
        };

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('must define NAME');

        $plugin::getName();
    }
}
