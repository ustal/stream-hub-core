<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Plugin\AbstractStreamPlugin;

final class CommandEnabledPlugin extends AbstractStreamPlugin
{
    public const NAME = 'command-enabled';

    public static function getCommandHandlers(): array
    {
        return [
            RecordingTestCommandHandler::class,
        ];
    }
}
