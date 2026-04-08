<?php

namespace Ustal\StreamHub\Component\Identifier\Generator;

use Ustal\StreamHub\Component\Identifier\IdentifierGeneratorInterface;

final class UuidV7IdentifierGenerator implements IdentifierGeneratorInterface
{
    public function generate(): string
    {
        $timestamp = (int) floor(microtime(true) * 1000);
        $timestampHex = str_pad(dechex($timestamp), 12, '0', STR_PAD_LEFT);
        $randomHex = bin2hex(random_bytes(10));

        $timeLow = substr($timestampHex, 0, 8);
        $timeMid = substr($timestampHex, 8, 4);
        $timeHighAndVersion = '7' . substr($randomHex, 0, 3);
        $clockSeq = dechex((hexdec(substr($randomHex, 3, 4)) & 0x3fff) | 0x8000);
        $clockSeq = str_pad($clockSeq, 4, '0', STR_PAD_LEFT);
        $node = substr($randomHex, 7, 12);

        return sprintf(
            '%s-%s-%s-%s-%s',
            $timeLow,
            $timeMid,
            $timeHighAndVersion,
            $clockSeq,
            $node,
        );
    }
}
