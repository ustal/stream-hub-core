<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Plugin\AbstractStreamPlugin;

class ConflictingReplacePlugin extends AbstractStreamPlugin
{
    public const NAME = 'conflicting-replace';

    public static function getWidgets(): array
    {
        return [
            ReplaceMainWidget::class,
            AnotherReplaceMainWidget::class,
        ];
    }
}
