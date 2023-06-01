<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Tests;

use JMS\Serializer\SerializationContext;
use WayOfDev\Serializer\Config;
use WayOfDev\Serializer\Contracts\ConfigRepository;
use WayOfDev\Serializer\ResponseFactory;
use WayOfDev\Serializer\SerializerFactory;
use WayOfDev\Serializer\Tests\Stubs\Entity;
use WayOfDev\Serializer\Tests\Stubs\Item;
use WayOfDev\Serializer\Tests\Stubs\Response;

class ResponseFactoryTest extends TestCase
{
    private ConfigRepository $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = Config::fromArray([
            'serialize_null' => true,
            'serialize_format' => Config::SERIALIZE_FORMAT_JSON,
            'debug' => true,
            'cache_dir' => storage_path('framework/cache'),
        ]);
    }

    /**
     * @test
     */
    public function it_creates_response(): void
    {
        $responseFactory = new ResponseFactory((new SerializerFactory())->getSerializer($this->config), $this->config);
        $response = $responseFactory->create(new Entity());

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('{"amount":777,"text":"Some String"}', $response->getContent());
    }

    /**
     * @test
     */
    public function it_creates_from_array_iterator(): void
    {
        $responseFactory = new ResponseFactory((new SerializerFactory())->getSerializer($this->config), $this->config);
        $response = $responseFactory->create(Response::create([new Item()]));

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('[{"key":"magic_number","value":12}]', $response->getContent());
    }

    /**
     * @test
     */
    public function it_creates_response_from_array(): void
    {
        $responseFactory = new ResponseFactory((new SerializerFactory())->getSerializer($this->config), $this->config);
        $response = $responseFactory->fromArray(require __DIR__ . '/Stubs/stub_array.php');

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals(
            '{"random_object":{"person":{"first_name":"Valery Albertovich","last_name":"Zhmyshenko","birthdate":"01.01.1976","birth_place":"Samara","nationality":"ukrainian"}}}',
            $response->getContent()
        );
    }

    /**
     * @test
     */
    public function it_sets_non_default_status_code(): void
    {
        $responseFactory = new ResponseFactory((new SerializerFactory())->getSerializer($this->config), $this->config);
        $responseFactory->withStatusCode(404);
        $response = $responseFactory->create(new Entity());

        self::assertEquals(404, $response->getStatusCode());
        self::assertEquals('{"amount":777,"text":"Some String"}', $response->getContent());
    }

    /**
     * @test
     */
    public function it_uses_given_context(): void
    {
        $responseFactory = new ResponseFactory((new SerializerFactory())->getSerializer($this->config), $this->config);
        $responseFactory->withContext(SerializationContext::create()->setSerializeNull(true));
        $response = $responseFactory->create(new Entity());

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('{"items":null,"amount":777,"text":"Some String"}', $response->getContent());
    }
}
