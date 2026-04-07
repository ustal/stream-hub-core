<?php

namespace Ustal\StreamHub\Core\Render;

use Ustal\StreamHub\Component\Render\RenderResult;

class Renderer
{
    public function __construct(private iterable $handlers) {}

    public function render(RenderResult $result): string
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($result)) {
                return $handler->render($result);
            }
        }

        throw new \RuntimeException('No renderer');
    }
}
