<?php

namespace Ustal\StreamHub\Component\Service;

use Ustal\StreamHub\Component\Exception\PluginConfigurationException;
use Ustal\StreamHub\Component\Plugin\StreamPluginInterface;
use Ustal\StreamHub\Component\Widget\StreamWidgetInterface;

final class PluginDefinitionBuilder
{
    /**
     * @param array<class-string<StreamPluginInterface>> $requiredPlugins
     */
    public function __construct(private array $requiredPlugins = [])
    {
    }

    /**
     * @param array<int, class-string<StreamPluginInterface>|array{class: class-string<StreamPluginInterface>, widgets?: array<class-string<StreamWidgetInterface>>, is_default?: bool}> $plugins
     * @param array<\BackedEnum> $rootSlots
     * @return PluginDefinitionRegistry
     */
    public function build(array $plugins, array $rootSlots = []): PluginDefinitionRegistry
    {
        $registry = new PluginDefinitionRegistry();

        foreach ($this->requiredPlugins as $pluginClass) {
            $registry->add(new PluginDefinition(
                $pluginClass::getName(),
                $pluginClass,
                $pluginClass::getCommandHandlers(),
                $pluginClass::getWidgets(),
                true,
            ));
        }

        foreach ($plugins as $plugin) {
            $pluginConfig = $this->normalizePluginConfig($plugin);
            $class = $pluginConfig['class'];

            if (in_array($class, $this->requiredPlugins, true)) {
                continue;
            }

            $widgets = $pluginConfig['widgets'] ?? $class::getWidgets();

            $registry->add(new PluginDefinition(
                $class::getName(),
                $class,
                $class::getCommandHandlers(),
                $this->resolveEnabledWidgets($class, $widgets),
                $pluginConfig['is_default'] ?? false,
            ));
        }

        (new PluginDefinitionValidator())->validate($registry, $rootSlots);

        return $registry;
    }

    /**
     * @param class-string<StreamPluginInterface>|array{class: class-string<StreamPluginInterface>, widgets?: array<class-string<StreamWidgetInterface>>, is_default?: bool} $plugin
     * @return array{class: class-string<StreamPluginInterface>, widgets?: array<class-string<StreamWidgetInterface>>, is_default?: bool}
     */
    private function normalizePluginConfig(string|array $plugin): array
    {
        if (is_string($plugin)) {
            return ['class' => $plugin];
        }

        return $plugin;
    }

    /**
     * @param class-string<StreamPluginInterface> $pluginClass
     * @param array<class-string<StreamWidgetInterface>> $enabledWidgets
     * @return array<class-string<StreamWidgetInterface>>
     */
    private function resolveEnabledWidgets(string $pluginClass, array $enabledWidgets): array
    {
        $availableWidgets = $pluginClass::getWidgets();
        $availableWidgetMap = array_fill_keys($availableWidgets, true);

        foreach ($enabledWidgets as $widgetClass) {
            if (!isset($availableWidgetMap[$widgetClass])) {
                throw new PluginConfigurationException(sprintf(
                    'Widget %s is not declared by plugin %s.',
                    $widgetClass,
                    $pluginClass
                ));
            }
        }

        return $enabledWidgets;
    }
}
