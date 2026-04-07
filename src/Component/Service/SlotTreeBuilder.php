<?php

namespace Ustal\StreamHub\Component\Service;

use Ustal\StreamHub\Component\Enum\SlotAcceptanceMode;
use Ustal\StreamHub\Component\Enum\WidgetPlacementMode;
use Ustal\StreamHub\Component\Exception\PluginConfigurationException;
use Ustal\StreamHub\Component\ValueObject\LayoutSlot;

final class SlotTreeBuilder
{
    /**
     * @param array<\BackedEnum> $rootSlots
     */
    public function build(PluginDefinitionRegistry $registry, array $rootSlots = []): SlotTree
    {
        $slots = [];
        foreach ($rootSlots as $slot) {
            $slots[$slot->value] = true;
            $slotAcceptanceModes[$slot->value] = SlotAcceptanceMode::ANY;
        }

        $assignmentsBySlot = [];
        $childSlotsBySlot = [];
        $graph = [];
        $slotAcceptanceModes = $slotAcceptanceModes ?? [];

        foreach ($registry->all() as $definition) {
            foreach ($definition->widgets as $widgetDefinition) {
                $widgetClass = $widgetDefinition->class;
                $targetSlot = $widgetDefinition->targetSlot;
                $providedSlots = $widgetClass::provideSlots();

                foreach ($providedSlots as $providedSlot) {
                    $this->assertValidProvidedSlot($widgetClass, $providedSlot);
                    $providedSlotName = $providedSlot->getLayoutSlot()->value;
                    $slots[$providedSlotName] = true;
                    $slotAcceptanceModes[$providedSlotName] = $providedSlot->getAcceptanceMode();
                }

                $assignment = new SlotAssignment(
                    pluginId: $definition->id,
                    widgetClass: $widgetClass,
                    targetSlot: $targetSlot,
                    placementMode: $widgetDefinition->placementMode,
                    providedSlots: $providedSlots,
                );

                $assignmentsBySlot[$targetSlot][] = $assignment;
                $graph[$targetSlot] ??= [];

                foreach ($providedSlots as $providedSlot) {
                    $childSlot = $providedSlot->getLayoutSlot()->value;
                    $graph[$targetSlot][] = $childSlot;
                    $childSlotsBySlot[$targetSlot][$childSlot] = true;
                }
            }
        }

        $this->validateTargetSlots($assignmentsBySlot, $slots, $slotAcceptanceModes);
        $this->validateReplaceConflicts($assignmentsBySlot);
        $this->validateCycles($graph);

        return new SlotTree(
            slots: $slots,
            assignmentsBySlot: $assignmentsBySlot,
            childSlotsBySlot: array_map(
                static fn (array $children): array => array_keys($children),
                $childSlotsBySlot
            ),
        );
    }

    /**
     * @param array<string, SlotAssignment[]> $assignmentsBySlot
     * @param array<string, true> $knownSlots
     * @param array<string, SlotAcceptanceMode> $slotAcceptanceModes
     */
    private function validateTargetSlots(array $assignmentsBySlot, array $knownSlots, array $slotAcceptanceModes): void
    {
        foreach ($assignmentsBySlot as $targetSlot => $assignments) {
            if (isset($knownSlots[$targetSlot])) {
                foreach ($assignments as $assignment) {
                    $this->assertPlacementIsAccepted(
                        $assignment,
                        $slotAcceptanceModes[$targetSlot] ?? SlotAcceptanceMode::ANY
                    );
                }

                continue;
            }

            throw new PluginConfigurationException(sprintf(
                'Widget %s targets unknown slot "%s".',
                $assignments[0]->widgetClass,
                $targetSlot
            ));
        }
    }

    /**
     * @param array<string, SlotAssignment[]> $assignmentsBySlot
     */
    private function validateReplaceConflicts(array $assignmentsBySlot): void
    {
        foreach ($assignmentsBySlot as $slot => $assignments) {
            $replaceCount = 0;

            foreach ($assignments as $assignment) {
                if ($assignment->placementMode === WidgetPlacementMode::REPLACE) {
                    $replaceCount++;
                }
            }

            if ($replaceCount > 1) {
                throw new PluginConfigurationException(sprintf(
                    'Slot "%s" cannot be replaced by more than one widget.',
                    $slot
                ));
            }

            if ($replaceCount === 1 && count($assignments) > 1) {
                throw new PluginConfigurationException(sprintf(
                    'Slot "%s" is already reserved by a replacing widget.',
                    $slot
                ));
            }
        }
    }

    private function assertPlacementIsAccepted(SlotAssignment $assignment, SlotAcceptanceMode $acceptanceMode): void
    {
        if ($acceptanceMode === SlotAcceptanceMode::ANY) {
            return;
        }

        if ($acceptanceMode === SlotAcceptanceMode::APPEND_ONLY
            && $assignment->placementMode === WidgetPlacementMode::APPEND
        ) {
            return;
        }

        if ($acceptanceMode === SlotAcceptanceMode::REPLACE_ONLY
            && $assignment->placementMode === WidgetPlacementMode::REPLACE
        ) {
            return;
        }

        throw new PluginConfigurationException(sprintf(
            'Widget %s with placement mode "%s" is not accepted by slot "%s".',
            $assignment->widgetClass,
            $assignment->placementMode->value,
            $assignment->targetSlot
        ));
    }

    /**
     * @param array<string, string[]> $graph
     */
    private function validateCycles(array $graph): void
    {
        $visited = [];
        $stack = [];

        foreach (array_keys($graph) as $slot) {
            $this->walkGraph($slot, $graph, $visited, $stack);
        }
    }

    /**
     * @param array<string, string[]> $graph
     * @param array<string, bool> $visited
     * @param array<string, bool> $stack
     */
    private function walkGraph(string $slot, array $graph, array &$visited, array &$stack): void
    {
        if (($stack[$slot] ?? false) === true) {
            throw new PluginConfigurationException(sprintf(
                'Slot graph contains a cycle at "%s".',
                $slot
            ));
        }

        if (($visited[$slot] ?? false) === true) {
            return;
        }

        $visited[$slot] = true;
        $stack[$slot] = true;

        foreach ($graph[$slot] ?? [] as $childSlot) {
            $this->walkGraph($childSlot, $graph, $visited, $stack);
        }

        unset($stack[$slot]);
    }

    private function assertValidProvidedSlot(string $widgetClass, mixed $slot): void
    {
        if (!$slot instanceof LayoutSlot) {
            throw new PluginConfigurationException(sprintf(
                'Widget %s must declare provided slots as %s instances.',
                $widgetClass,
                LayoutSlot::class
            ));
        }
    }

}
