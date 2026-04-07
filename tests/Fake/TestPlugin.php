<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Plugin\AbstractStreamPlugin;

class TestPlugin extends AbstractStreamPlugin
{
    public const NAME = 'my-plugin_name';
}

class UppercasePlugin extends AbstractStreamPlugin
{
    public const NAME = 'My-Plugin_Name';
}
