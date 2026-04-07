<?php

namespace Ustal\StreamHub\Component\Service;

final class PluginDefinitionValidator
{
    /**
     * @param array<\BackedEnum> $rootSlots
     */
    public function validate(PluginDefinitionRegistry $registry, array $rootSlots = []): void
    {
        (new SlotTreeBuilder())->build($registry, $rootSlots);
    }
}
