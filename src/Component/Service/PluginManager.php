<?php

namespace Ustal\StreamHub\Component\Service;

use Ustal\StreamHub\Component\Plugin\StreamPluginCSSInterface;
use Ustal\StreamHub\Component\Plugin\StreamPluginJSInterface;

class PluginManager
{
    public function __construct(private PluginDefinitionRegistry $registry) {}

    /**
     * @return PluginDefinition[]
     */
    public function getPlugins(): array
    {
        return $this->registry->all();
    }

    public function has(string $pluginId): bool
    {
        return $this->registry->has($pluginId);
    }

    public function get(string $pluginId): PluginDefinition
    {
        return $this->registry->get($pluginId);
    }

    /**
     * @return array<string, array{class: string, name: string, js: string[], css: string[]}>
     */
    public function getPublicAssets(): array
    {
        $assets = [];

        foreach ($this->registry->all() as $definition) {
            $pluginClass = $definition->class;
            $assets[$definition->id] = [
                'class' => $pluginClass,
                'name' => $pluginClass::getName(),
                'js' => is_subclass_of($pluginClass, StreamPluginJSInterface::class) ? $pluginClass::getJSFiles() : [],
                'css' => is_subclass_of($pluginClass, StreamPluginCSSInterface::class) ? $pluginClass::getCSSFiles() : [],
            ];
        }

        return $assets;
    }
}
