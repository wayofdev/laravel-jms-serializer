<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Tests;

use JMS\Serializer\Serializer;
use WayOfDev\Serializer\Config;
use WayOfDev\Serializer\SerializerFactory;

class SerializerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_create_serializer_instance(): void
    {
        $config = Config::fromArray([
            'serialize_null' => true,
            'serialize_format' => Config::SERIALIZE_FORMAT_JSON,
            'debug' => true,
            'cache_dir' => storage_path('framework/cache/serializer'),
        ]);

        $serializer = (new SerializerFactory())
            ->getSerializer($config);

        self::assertInstanceOf(Serializer::class, $serializer);
    }
}
