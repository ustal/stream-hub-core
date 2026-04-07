<?php

namespace Ustal\StreamHub\Component\Service;

class PluginDefinitionRegistry
{
    /** @var PluginDefinition[] */
    private array $plugins = [];

    public function add(PluginDefinition $plugin): void
    {
        $this->plugins[$plugin->id] = $plugin;
    }

    /** @return PluginDefinition[] */
    public function all(): array
    {
        return $this->plugins;
    }

    public function has(string $id): bool
    {
        return isset($this->plugins[$id]);
    }

    public function get(string $id): PluginDefinition
    {
        return $this->plugins[$id];
    }
}
