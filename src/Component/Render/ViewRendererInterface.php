<?php

namespace Ustal\StreamHub\Component\Render;

interface ViewRendererInterface
{
    public function getName(): string;

    public function render(string $template, array $context = []): string;
}
