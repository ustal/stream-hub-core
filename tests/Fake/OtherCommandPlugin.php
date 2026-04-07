<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Plugin\AbstractStreamPlugin;

final class OtherCommandPlugin extends AbstractStreamPlugin
{
    public const NAME = 'other-command';

    public static function getCommandHandlers(): array
    {
        return [
            RecordingOtherCommandHandler::class,
        ];
    }
}
