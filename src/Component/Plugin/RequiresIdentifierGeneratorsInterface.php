<?php

namespace Ustal\StreamHub\Component\Plugin;

interface RequiresIdentifierGeneratorsInterface
{
    /**
     * @return list<string>
     */
    public static function getIdentifierGeneratorRequirements(): array;
}
