<?php

namespace Ustal\StreamHub\Component\Render;

class HtmlRenderResult extends RenderResult
{
    public function __construct(public string $html) {}

    public function supports(RenderResult $result): bool
    {
        return $result instanceof HtmlRenderResult;
    }

    public function render(RenderResult $result): string
    {
        return $result->html;
    }
}
