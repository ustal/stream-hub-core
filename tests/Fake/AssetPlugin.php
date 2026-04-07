<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Plugin\AbstractStreamPlugin;
use Ustal\StreamHub\Component\Plugin\StreamPluginCSSInterface;
use Ustal\StreamHub\Component\Plugin\StreamPluginJSInterface;

final class AssetPlugin extends AbstractStreamPlugin implements StreamPluginJSInterface, StreamPluginCSSInterface
{
    public const NAME = 'asset-plugin';

    public static function getCSSFiles(): array
    {
        return [
            'plugin.css',
            'test.css',
        ];
    }

    public static function getJSFiles(): array
    {
        return [
            'plugin.js',
        ];
    }
}
