<?php

namespace Ustal\StreamHub\Component\CommandBus;

use Ustal\StreamHub\Component\Context\StreamContextInterface;

interface StreamCommandHandlerInterface
{
    public function handle(StreamCommandInterface $command, StreamContextInterface $context): void;

    /**
     * @return class-string<StreamCommandInterface>
     */
    public static function supports(): string;
}
