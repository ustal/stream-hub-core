<?php

namespace Ustal\StreamHub\Core\Render;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Core\StreamRuntime;

interface StreamPageRendererInterface
{
    public function render(StreamRuntime $runtime, StreamContextInterface $context): string;
}
