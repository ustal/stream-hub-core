<?php

namespace Ustal\StreamHub\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Enum\DefaultSlotName;
use Ustal\StreamHub\Component\Enum\WidgetPlacementMode;
use Ustal\StreamHub\Component\Exception\PluginConfigurationException;
use Ustal\StreamHub\Component\Service\PluginDefinitionBuilder;
use Ustal\StreamHub\Component\Widget\WidgetRegistry;
use Ustal\StreamHub\Core\Plugins\CoreStream\CoreStreamPlugin;
use Ustal\StreamHub\Tests\Fake\ConflictingReplacePlugin;
use Ustal\StreamHub\Tests\Fake\DefinitionTestPlugin;
use Ustal\StreamHub\Tests\Fake\DetailsWidget;
use Ustal\StreamHub\Tests\Fake\MainContainerWidget;
use Ustal\StreamHub\Tests\Fake\OrphanWidget;
use Ustal\StreamHub\Tests\Fake\OrphanWidgetPlugin;
use Ustal\StreamHub\Tests\Fake\TestSlot;

class PluginDefinitionBuilderTest extends TestCase
{
    public function testBuildEnablesAllPluginWidgetsByDefault(): void
    {
        $registry = (new PluginDefinitionBuilder([CoreStreamPlugin::class]))->build(
            [DefinitionTestPlugin::class],
            [DefaultSlotName::MAIN]
        );

        $this->assertTrue($registry->has(CoreStreamPlugin::getName()));
        $definition = $registry->get(DefinitionTestPlugin::getName());

        $this->assertSame(DefinitionTestPlugin::class, $definition->class);
        $this->assertSame(
            [
                MainContainerWidget::class,
                DetailsWidget::class,
            ],
            $definition->widgetClasses
        );
    }

    public function testBuildCanEnableOnlyConfiguredWidgets(): void
    {
        $registry = (new PluginDefinitionBuilder([CoreStreamPlugin::class]))->build(
            [[
                'class' => DefinitionTestPlugin::class,
                'widgets' => [
                    MainContainerWidget::class,
                    DetailsWidget::class,
                ],
            ]],
            [DefaultSlotName::MAIN]
        );

        $definition = $registry->get(DefinitionTestPlugin::getName());

        $this->assertSame(
            [
                MainContainerWidget::class,
                DetailsWidget::class,
            ],
            $definition->widgetClasses
        );
    }

    public function testBuildCanOverrideWidgetPlacementConfiguration(): void
    {
        $registry = (new PluginDefinitionBuilder([CoreStreamPlugin::class]))->build(
            [[
                'class' => DefinitionTestPlugin::class,
                'widgets' => [[
                    'class' => DetailsWidget::class,
                    'slot' => DefaultSlotName::MAIN,
                    'placement' => WidgetPlacementMode::REPLACE,
                ]],
            ]],
            [DefaultSlotName::MAIN]
        );

        $definition = $registry->get(DefinitionTestPlugin::getName());

        $this->assertCount(1, $definition->widgets);
        $this->assertSame(DetailsWidget::class, $definition->widgets[0]->class);
        $this->assertSame(DefaultSlotName::MAIN->value, $definition->widgets[0]->targetSlot);
        $this->assertSame(WidgetPlacementMode::REPLACE, $definition->widgets[0]->placementMode);
        $this->assertSame([DetailsWidget::class], $definition->widgetClasses);
    }

    public function testBuildRegistersRequiredPluginFirst(): void
    {
        $registry = (new PluginDefinitionBuilder([CoreStreamPlugin::class]))->build(
            [DefinitionTestPlugin::class],
            [DefaultSlotName::MAIN]
        );

        $definitions = array_values($registry->all());

        $this->assertSame(CoreStreamPlugin::getName(), $definitions[0]->id);
        $this->assertTrue($definitions[0]->isDefault);
        $this->assertNotEmpty($definitions[0]->handlerClasses);
    }

    public function testBuildRejectsWidgetThatPluginDoesNotDeclare(): void
    {
        $this->expectException(PluginConfigurationException::class);
        $this->expectExceptionMessage('is not declared by plugin');

        (new PluginDefinitionBuilder([CoreStreamPlugin::class]))->build(
            [[
                'class' => DefinitionTestPlugin::class,
                'widgets' => [OrphanWidget::class],
            ]],
            [DefaultSlotName::MAIN]
        );
    }

    public function testBuildRejectsWidgetTargetingUnknownSlot(): void
    {
        $this->expectException(PluginConfigurationException::class);
        $this->expectExceptionMessage('targets unknown slot');

        (new PluginDefinitionBuilder([CoreStreamPlugin::class]))->build(
            [[
                'class' => OrphanWidgetPlugin::class,
                'widgets' => [OrphanWidget::class],
            ]],
            [DefaultSlotName::MAIN]
        );
    }

    public function testBuildRejectsConflictingReplaceWidgets(): void
    {
        $this->expectException(PluginConfigurationException::class);
        $this->expectExceptionMessage('cannot be replaced by more than one widget');

        (new PluginDefinitionBuilder([CoreStreamPlugin::class]))->build(
            [ConflictingReplacePlugin::class],
            [DefaultSlotName::MAIN]
        );
    }

    public function testWidgetRegistryIndexesEnabledWidgetClassesBySlot(): void
    {
        $registry = (new PluginDefinitionBuilder([CoreStreamPlugin::class]))->build(
            [[
                'class' => DefinitionTestPlugin::class,
                'widgets' => [
                    MainContainerWidget::class,
                    DetailsWidget::class,
                ],
            ]],
            [DefaultSlotName::MAIN]
        );

        $definition = $registry->get(DefinitionTestPlugin::getName());
        $widgetRegistry = new WidgetRegistry($definition->widgetClasses);

        $this->assertSame(
            [MainContainerWidget::class],
            $widgetRegistry->getBySlot(DefaultSlotName::MAIN)
        );
        $this->assertSame(
            [DetailsWidget::class],
            $widgetRegistry->getBySlot(TestSlot::DETAILS)
        );
    }
}
