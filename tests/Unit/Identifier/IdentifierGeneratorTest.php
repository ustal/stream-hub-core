<?php

namespace Ustal\StreamHub\Tests\Unit\Identifier;

use PHPUnit\Framework\TestCase;
use Ustal\StreamHub\Component\Identifier\Generator\RandomHexIdentifierGenerator;
use Ustal\StreamHub\Component\Identifier\Generator\UuidV4IdentifierGenerator;
use Ustal\StreamHub\Component\Identifier\Generator\UuidV7IdentifierGenerator;

final class IdentifierGeneratorTest extends TestCase
{
    public function testRandomHexGeneratorProducesConfiguredLength(): void
    {
        $generator = new RandomHexIdentifierGenerator(8);

        $id = $generator->generate();

        self::assertMatchesRegularExpression('/^[a-f0-9]{16}$/', $id);
    }

    public function testUuidV4GeneratorProducesVersion4Uuid(): void
    {
        $id = (new UuidV4IdentifierGenerator())->generate();

        self::assertMatchesRegularExpression(
            '/^[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/',
            $id
        );
    }

    public function testUuidV7GeneratorProducesVersion7Uuid(): void
    {
        $id = (new UuidV7IdentifierGenerator())->generate();

        self::assertMatchesRegularExpression(
            '/^[a-f0-9]{8}-[a-f0-9]{4}-7[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/',
            $id
        );
    }
}
