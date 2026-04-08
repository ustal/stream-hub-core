<?php

namespace Ustal\StreamHub\Component\Service;

use Ustal\StreamHub\Component\Exception\PluginConfigurationException;
use Ustal\StreamHub\Component\Enum\WidgetPlacementMode;
use Ustal\StreamHub\Component\Plugin\RequiresIdentifierGeneratorsInterface;
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
     * @param array<int, class-string<StreamPluginInterface>|array{class: class-string<StreamPluginInterface>, widgets?: array<int|string, class-string<StreamWidgetInterface>|array{class?: class-string<StreamWidgetInterface>, slot?: \BackedEnum, placement?: WidgetPlacementMode}>, is_default?: bool}> $plugins
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
                $this->resolveEnabledWidgets($pluginClass, $pluginClass::getWidgets()),
                $pluginClass::getWidgets(),
                true,
                $this->resolveIdentifierGeneratorRequirements($pluginClass),
            ));
        }

        foreach ($plugins as $plugin) {
            $pluginConfig = $this->normalizePluginConfig($plugin);
            $class = $pluginConfig['class'];

            if (in_array($class, $this->requiredPlugins, true)) {
                continue;
            }

            $widgets = $pluginConfig['widgets'] ?? $class::getWidgets();
            $resolvedWidgets = $this->resolveEnabledWidgets($class, $widgets);

            $registry->add(new PluginDefinition(
                $class::getName(),
                $class,
                $class::getCommandHandlers(),
                $resolvedWidgets,
                array_map(
                    static fn (WidgetDefinition $definition): string => $definition->class,
                    $resolvedWidgets
                ),
                $pluginConfig['is_default'] ?? false,
                $this->resolveIdentifierGeneratorRequirements($class),
            ));
        }

        (new PluginDefinitionValidator())->validate($registry, $rootSlots);

        return $registry;
    }

    /**
     * @param class-string<StreamPluginInterface>|array{class: class-string<StreamPluginInterface>, widgets?: array<int|string, class-string<StreamWidgetInterface>|array{class?: class-string<StreamWidgetInterface>, slot?: \BackedEnum, placement?: WidgetPlacementMode}>, is_default?: bool} $plugin
     * @return array{class: class-string<StreamPluginInterface>, widgets?: array<int|string, class-string<StreamWidgetInterface>|array{class?: class-string<StreamWidgetInterface>, slot?: \BackedEnum, placement?: WidgetPlacementMode}>, is_default?: bool}
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
     * @param array<int|string, class-string<StreamWidgetInterface>|array{class?: class-string<StreamWidgetInterface>, slot?: \BackedEnum, placement?: WidgetPlacementMode}> $enabledWidgets
     * @return WidgetDefinition[]
     */
    private function resolveEnabledWidgets(string $pluginClass, array $enabledWidgets): array
    {
        $availableWidgets = $pluginClass::getWidgets();
        $availableWidgetMap = array_fill_keys($availableWidgets, true);
        $resolved = [];

        foreach ($enabledWidgets as $key => $widgetConfig) {
            [$widgetClass, $normalizedWidgetConfig] = $this->normalizeWidgetConfig($key, $widgetConfig);

            if (!isset($availableWidgetMap[$widgetClass])) {
                throw new PluginConfigurationException(sprintf(
                    'Widget %s is not declared by plugin %s.',
                    $widgetClass,
                    $pluginClass
                ));
            }

            $resolved[] = new WidgetDefinition(
                class: $widgetClass,
                targetSlot: $this->resolveWidgetSlot($widgetClass, $normalizedWidgetConfig),
                placementMode: $this->resolveWidgetPlacementMode($widgetClass, $normalizedWidgetConfig),
            );
        }

        return $resolved;
    }

    /**
     * @param int|string $key
     * @param class-string<StreamWidgetInterface>|array{class?: class-string<StreamWidgetInterface>, slot?: \BackedEnum, placement?: WidgetPlacementMode} $widgetConfig
     * @return array{class-string<StreamWidgetInterface>, array{slot?: \BackedEnum, placement?: WidgetPlacementMode}}
     */
    private function normalizeWidgetConfig(int|string $key, string|array $widgetConfig): array
    {
        if (is_string($widgetConfig)) {
            return [$widgetConfig, []];
        }

        if (isset($widgetConfig['class'])) {
            $widgetClass = $widgetConfig['class'];
            unset($widgetConfig['class']);

            return [$widgetClass, $widgetConfig];
        }

        if (!is_string($key)) {
            throw new PluginConfigurationException('Widget configuration array must declare a class.');
        }

        return [$key, $widgetConfig];
    }

    /**
     * @param array{slot?: \BackedEnum, placement?: WidgetPlacementMode} $widgetConfig
     */
    private function resolveWidgetSlot(string $widgetClass, array $widgetConfig): string
    {
        if (isset($widgetConfig['slot'])) {
            return $widgetConfig['slot']->value;
        }

        return $widgetClass::getSlot()->value;
    }

    /**
     * @param array{slot?: \BackedEnum, placement?: WidgetPlacementMode} $widgetConfig
     */
    private function resolveWidgetPlacementMode(string $widgetClass, array $widgetConfig): WidgetPlacementMode
    {
        if (isset($widgetConfig['placement'])) {
            return $widgetConfig['placement'];
        }

        return $widgetClass::getPlacementMode();
    }

    /**
     * @param class-string<StreamPluginInterface> $pluginClass
     * @return list<string>
     */
    private function resolveIdentifierGeneratorRequirements(string $pluginClass): array
    {
        if (!is_subclass_of($pluginClass, RequiresIdentifierGeneratorsInterface::class)) {
            return [];
        }

        $requirements = $pluginClass::getIdentifierGeneratorRequirements();

        foreach ($requirements as $requirement) {
            if (!is_string($requirement) || $requirement === '') {
                throw new PluginConfigurationException(sprintf(
                    'Plugin %s must declare non-empty identifier generator requirement names.',
                    $pluginClass
                ));
            }
        }

        return array_values(array_unique($requirements));
    }
}
