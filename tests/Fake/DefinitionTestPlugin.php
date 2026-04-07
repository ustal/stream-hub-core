<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Plugin\AbstractStreamPlugin;

class DefinitionTestPlugin extends AbstractStreamPlugin
{
    public const NAME = 'definition-test';

    public static function getWidgets(): array
    {
        return [
            MainContainerWidget::class,
            DetailsWidget::class,
        ];
    }
}
