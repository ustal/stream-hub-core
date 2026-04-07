<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Context\StreamContextInterface;
use Ustal\StreamHub\Component\Enum\DefaultSlotName;
use Ustal\StreamHub\Component\Render\RenderResult;
use Ustal\StreamHub\Component\Widget\AbstractStreamWidget;

final class TemplatedWidget extends AbstractStreamWidget
{
    public static function getName(): string
    {
        return 'templated';
    }

    public static function getSlot(): \BackedEnum
    {
        return DefaultSlotName::MAIN;
    }

    public static function supports(StreamContextInterface $context): bool
    {
        return true;
    }

    public function isVisible(StreamContextInterface $context): bool
    {
        return true;
    }

    public static function getTemplates(): array
    {
        return [
            'twig' => 'widget/templated.html.twig',
            'blade' => 'widget/templated.blade.php',
        ];
    }

    public function render(StreamContextInterface $context): RenderResult
    {
        return $this->renderTemplate($context, [
            'message' => 'hello',
        ]);
    }
}
