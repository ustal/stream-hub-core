<?php

namespace Ustal\StreamHub\Component\Enum;

enum StreamEventType: string
{
    case MESSAGE = 'message';
    case SYSTEM = 'system';
}
