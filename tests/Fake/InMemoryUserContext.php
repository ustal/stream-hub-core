<?php

namespace Ustal\StreamHub\Tests\Fake;

use Ustal\StreamHub\Component\Context\StreamContextInterface;

final readonly class InMemoryUserContext implements StreamContextInterface
{
    public function __construct(
        private string $userId = 'user-1',
        private string $actor = 'candidate',
        private array $values = [],
    ) {}

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getActor(): ?string
    {
        return $this->actor;
    }

    public function generateUrl(string $name, array $parameters = []): string
    {
        if ($parameters === []) {
            return '/generated/' . $name;
        }

        return '/generated/' . $name . '?' . http_build_query($parameters);
    }

    public function getCsrfToken(string $intention): ?string
    {
        return 'csrf-' . $intention;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->values);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->values[$key] ?? $default;
    }
}
