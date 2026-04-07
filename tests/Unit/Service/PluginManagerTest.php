<?php

namespace Ustal\StreamHub\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Enum\DefaultSlotName;
use Ustal\StreamHub\Component\Service\PluginDefinitionBuilder;
use Ustal\StreamHub\Component\Service\PluginManager;
use Ustal\StreamHub\Core\Plugins\CoreStream\CoreStreamPlugin;
use Ustal\StreamHub\Tests\Fake\AssetPlugin;

class PluginManagerTest extends TestCase
{
    public function testItExposesResolvedPluginsAndBuildsPublicAssetsMapFromStaticCapabilities(): void
    {
        $registry = (new PluginDefinitionBuilder())->build(
            [AssetPlugin::class],
            [DefaultSlotName::MAIN]
        );
        $manager = new PluginManager($registry);

        $this->assertTrue($manager->has(CoreStreamPlugin::getName()));
        $this->assertTrue($manager->has(AssetPlugin::getName()));
        $this->assertSame(AssetPlugin::class, $manager->get(AssetPlugin::getName())->class);
        $this->assertSame(
            [
                'class' => AssetPlugin::class,
                'name' => AssetPlugin::getName(),
                'js' => ['plugin.js'],
                'css' => ['plugin.css', 'test.css'],
            ],
            $manager->getPublicAssets()[AssetPlugin::getName()]
        );
        $this->assertSame(
            [
                'class' => CoreStreamPlugin::class,
                'name' => CoreStreamPlugin::getName(),
                'js' => [],
                'css' => [],
            ],
            $manager->getPublicAssets()[CoreStreamPlugin::getName()]
        );
    }
}
