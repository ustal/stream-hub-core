<?php

namespace Ustal\StreamHub\Component\ValueObject;


use Ustal\StreamHub\Component\Enum\SlotAcceptanceMode;

class LayoutSlot
{
    private \BackedEnum $layoutSlot;
    private SlotAcceptanceMode $acceptanceMode;

    public function __construct(\BackedEnum $layoutSlot, SlotAcceptanceMode $acceptanceMode)
    {
        $this->layoutSlot = $layoutSlot;
        $this->acceptanceMode = $acceptanceMode;
    }

    public function getLayoutSlot(): \BackedEnum
    {
        return $this->layoutSlot;
    }

    public function getAcceptanceMode(): SlotAcceptanceMode
    {
        return $this->acceptanceMode;
    }
}
