<?php

namespace Ustal\StreamHub\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Exception\PluginConfigurationException;
use Ustal\StreamHub\Component\Plugin\AbstractStreamPlugin;
use Ustal\StreamHub\Component\Plugin\RequiresIdentifierGeneratorsInterface;
use Ustal\StreamHub\Component\Service\PluginDefinitionBuilder;

final class PluginDefinitionBuilderIdentifierRequirementTest extends TestCase
{
    public function testItCarriesPluginIdentifierGeneratorRequirementsIntoDefinition(): void
    {
        $registry = (new PluginDefinitionBuilder())->build([
            FakePluginWithIdentifierRequirements::class,
        ]);

        self::assertSame(
            ['event_id', 'attachment_id'],
            $registry->get(FakePluginWithIdentifierRequirements::getName())->identifierGeneratorRequirements
        );
    }

    public function testItRejectsEmptyIdentifierGeneratorRequirementNames(): void
    {
        $this->expectException(PluginConfigurationException::class);
        $this->expectExceptionMessage('identifier generator requirement names');

        (new PluginDefinitionBuilder())->build([
            FakePluginWithInvalidIdentifierRequirements::class,
        ]);
    }
}

final class FakePluginWithIdentifierRequirements extends AbstractStreamPlugin implements RequiresIdentifierGeneratorsInterface
{
    public const NAME = 'fake-plugin-with-id-requirements';

    public static function getIdentifierGeneratorRequirements(): array
    {
        return ['event_id', 'attachment_id', 'event_id'];
    }
}

final class FakePluginWithInvalidIdentifierRequirements extends AbstractStreamPlugin implements RequiresIdentifierGeneratorsInterface
{
    public const NAME = 'fake-plugin-with-invalid-id-requirements';

    public static function getIdentifierGeneratorRequirements(): array
    {
        return [''];
    }
}
