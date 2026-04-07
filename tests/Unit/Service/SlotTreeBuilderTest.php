<?php

namespace Ustal\StreamHub\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Enum\DefaultSlotName;
use Ustal\StreamHub\Component\Exception\PluginConfigurationException;
use Ustal\StreamHub\Component\Service\PluginDefinitionBuilder;
use Ustal\StreamHub\Component\Service\SlotTreeBuilder;
use Ustal\StreamHub\Tests\Fake\CyclicSlotPlugin;
use Ustal\StreamHub\Tests\Fake\DefinitionTestPlugin;
use Ustal\StreamHub\Tests\Fake\DetailsWidget;
use Ustal\StreamHub\Tests\Fake\MainContainerWidget;
use Ustal\StreamHub\Tests\Fake\ReversedDefinitionTestPlugin;
use Ustal\StreamHub\Tests\Fake\TestSlot;

class SlotTreeBuilderTest extends TestCase
{
    public function testBuildResolvesSlotAssignmentsAndChildren(): void
    {
        $registry = (new PluginDefinitionBuilder())->build(
            [DefinitionTestPlugin::class],
            [DefaultSlotName::MAIN]
        );

        $tree = (new SlotTreeBuilder())->build($registry, [DefaultSlotName::MAIN]);

        $this->assertTrue($tree->hasSlot(DefaultSlotName::MAIN));
        $this->assertTrue($tree->hasSlot(TestSlot::DETAILS));
        $this->assertSame(
            [MainContainerWidget::class],
            array_map(
                static fn ($assignment): string => $assignment->widgetClass,
                $tree->getAssignmentsForSlot(DefaultSlotName::MAIN)
            )
        );
        $this->assertSame(
            [DetailsWidget::class],
            array_map(
                static fn ($assignment): string => $assignment->widgetClass,
                $tree->getAssignmentsForSlot(TestSlot::DETAILS)
            )
        );
        $this->assertSame(
            [TestSlot::DETAILS->value],
            $tree->getChildSlots(DefaultSlotName::MAIN)
        );
    }

    public function testBuildIsIndependentFromWidgetDeclarationOrder(): void
    {
        $registry = (new PluginDefinitionBuilder())->build(
            [ReversedDefinitionTestPlugin::class],
            [DefaultSlotName::MAIN]
        );

        $tree = (new SlotTreeBuilder())->build($registry, [DefaultSlotName::MAIN]);

        $this->assertTrue($tree->hasSlot(TestSlot::DETAILS));
        $this->assertCount(1, $tree->getAssignmentsForSlot(DefaultSlotName::MAIN));
        $this->assertCount(1, $tree->getAssignmentsForSlot(TestSlot::DETAILS));
    }

    public function testBuildRejectsCyclicSlotGraph(): void
    {
        $this->expectException(PluginConfigurationException::class);
        $this->expectExceptionMessage('contains a cycle');

        $registry = (new PluginDefinitionBuilder())->build(
            [CyclicSlotPlugin::class],
            [DefaultSlotName::MAIN]
        );

        (new SlotTreeBuilder())->build($registry, [DefaultSlotName::MAIN]);
    }
}
