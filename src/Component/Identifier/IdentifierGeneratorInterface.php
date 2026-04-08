<?php

namespace Ustal\StreamHub\Component\Identifier;

interface IdentifierGeneratorInterface
{
    public function generate(): string;
}
