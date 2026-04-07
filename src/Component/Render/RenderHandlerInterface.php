<?php

namespace Ustal\StreamHub\Component\Render;

interface RenderHandlerInterface
{
    public function supports(RenderResult $result): bool;

    public function render(RenderResult $result): string;
}
