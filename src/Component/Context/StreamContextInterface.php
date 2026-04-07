<?php

namespace Ustal\StreamHub\Component\Context;

interface StreamContextInterface
{
    public function getUserId(): string;

    public function getActor(): ?string;

    public function generateUrl(string $name, array $parameters = []): string;

    public function getCsrfToken(string $intention): ?string;

    public function has(string $key): bool;

    public function get(string $key, mixed $default = null): mixed;
}
