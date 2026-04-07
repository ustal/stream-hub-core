<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Plugin\AbstractStreamPlugin;

class ReversedDefinitionTestPlugin extends AbstractStreamPlugin
{
    public const NAME = 'reversed-definition-test';

    public static function getWidgets(): array
    {
        return [
            DetailsWidget::class,
            MainContainerWidget::class,
        ];
    }
}
