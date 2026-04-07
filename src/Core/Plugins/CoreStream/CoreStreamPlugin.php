<?php

namespace Ustal\StreamHub\Core\Plugins\CoreStream;

use Ustal\StreamHub\Component\Plugin\AbstractStreamPlugin;
use Ustal\StreamHub\Component\Plugin\StreamPluginJSInterface;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\AppendStreamEventCommandHandler;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\CreateStreamCommandHandler;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\JoinStreamCommandHandler;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\MarkStreamReadCommandHandler;

final class CoreStreamPlugin extends AbstractStreamPlugin implements StreamPluginJSInterface
{
    public const NAME = 'core';

    public static function getCommandHandlers(): array
    {
        return [
            CreateStreamCommandHandler::class,
            JoinStreamCommandHandler::class,
            AppendStreamEventCommandHandler::class,
            MarkStreamReadCommandHandler::class,
        ];
    }

    public static function getJSFiles(): array
    {
        return [
            'src/Core/Plugins/CoreStream/Resources/public/stream-hub.js',
        ];
    }
}
