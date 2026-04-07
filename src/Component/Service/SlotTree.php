<?php

namespace Ustal\StreamHub\Component\Service;

final readonly class SlotTree
{
    /**
     * @param array<string, true> $slots
     * @param array<string, SlotAssignment[]> $assignmentsBySlot
     * @param array<string, string[]> $childSlotsBySlot
     */
    public function __construct(
        private array $slots,
        private array $assignmentsBySlot,
        private array $childSlotsBySlot,
    ) {}

    public function hasSlot(string|\BackedEnum $slot): bool
    {
        return isset($this->slots[$this->normalizeSlot($slot)]);
    }

    /**
     * @return SlotAssignment[]
     */
    public function getAssignmentsForSlot(string|\BackedEnum $slot): array
    {
        return $this->assignmentsBySlot[$this->normalizeSlot($slot)] ?? [];
    }

    /**
     * @return string[]
     */
    public function getChildSlots(string|\BackedEnum $slot): array
    {
        return $this->childSlotsBySlot[$this->normalizeSlot($slot)] ?? [];
    }

    /**
     * @return string[]
     */
    public function allSlots(): array
    {
        return array_keys($this->slots);
    }

    private function normalizeSlot(string|\BackedEnum $slot): string
    {
        return is_string($slot) ? $slot : $slot->value;
    }
}
