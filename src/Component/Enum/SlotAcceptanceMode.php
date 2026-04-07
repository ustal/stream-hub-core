<?php

namespace Ustal\StreamHub\Component\Enum;

enum SlotAcceptanceMode: string
{
    case APPEND_ONLY = 'append-only';
    case REPLACE_ONLY = 'replace-only';
    case ANY = 'any';
}
