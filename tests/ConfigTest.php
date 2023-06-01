<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Tests;

use WayOfDev\Serializer\Config;
use WayOfDev\Serializer\Exceptions\MissingRequiredAttributes;
use function sprintf;

class ConfigTest extends TestCase
{
    public static function dataProviderForConfig(): array
    {
        return [
            'success' => [
                [
                    'serialize_null' => false,
                    'cache_dir' => '/storage',
                    'serialize_format' => 'json',
                    'debug' => false,
                ],
                false,
            ],
            'missing_required_fields' => [
                [
                    'serialize_null' => false,
                    'cache_dir' => '/storage',
                    'serialize_format' => 'json',
                ],
                true,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderForConfig
     */
    public function it_creates_config(array $config, bool $throwMissingException): void
    {
        if ($throwMissingException) {
            $this->expectException(MissingRequiredAttributes::class);
        }

        $configToTest = Config::fromArray($config);

        self::assertEquals($config['serialize_null'], $configToTest->serializeNull());
        self::assertEquals(sprintf('%s%s', $config['cache_dir'], '/serializer/'), $configToTest->cacheDir());
        self::assertEquals($config['serialize_format'], $configToTest->serializeFormat());
        self::assertEquals($config['debug'], $configToTest->debug());
    }
}
