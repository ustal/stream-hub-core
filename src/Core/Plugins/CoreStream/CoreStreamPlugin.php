<?php

namespace Ustal\StreamHub\Core\Plugins\CoreStream;

use Ustal\StreamHub\Component\Plugin\AbstractStreamPlugin;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\AppendStreamEventCommandHandler;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\CreateStreamCommandHandler;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\JoinStreamCommandHandler;
use Ustal\StreamHub\Core\Plugins\CoreStream\Command\MarkStreamReadCommandHandler;

final class CoreStreamPlugin extends AbstractStreamPlugin
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
}
