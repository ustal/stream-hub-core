<?php

namespace Ustal\StreamHub\Core\Render;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Core\StreamRuntime;

interface SlotRendererInterface
{
    public function render(\BackedEnum $slot, StreamRuntime $runtime, StreamContextInterface $context): string;
}
