<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use ArrayIterator;
use Illuminate\Http\Response;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use WayOfDev\Serializer\Contracts\ConfigRepository;

final class ResponseFactory
{
    private SerializerInterface $serializer;

    private ?SerializationContext $context = null;

    private ConfigRepository $config;

    private int $status = HttpCode::HTTP_OK;

    private string $serializeFormat;

    public function __construct(SerializerInterface $serializer, ConfigRepository $config)
    {
        $this->serializer = $serializer;
        $this->config = $config;
        $this->serializeFormat = $config->serializeFormat();
    }

    public function withStatusCode(int $code): void
    {
        $this->status = $code;
    }

    public function withContext(SerializationContext $context): void
    {
        $this->context = $context;
    }

    public function withSerializeFormat(string $serializeFormat): self
    {
        $instance = new self($this->serializer, $this->config);
        $instance->serializeFormat = $serializeFormat;

        return $instance;
    }

    public function create(object $jmsResponse): Response
    {
        $content = $this->serializer->serialize(
            $jmsResponse,
            $this->serializeFormat,
            $this->context,
            $this->getInitialType($jmsResponse)
        );

        return $this->respondWithJson($content, $this->status);
    }

    public function fromArray(array $jmsResponse): Response
    {
        $content = $this->serializer->serialize(
            $jmsResponse,
            $this->serializeFormat,
            $this->context
        );

        return $this->respondWithJson($content, $this->status);
    }

    private function respondWithJson($content, int $status): Response
    {
        return new Response(
            $content,
            $status,
            ['Content-Type' => 'application/json']
        );
    }

    private function getInitialType(object $jmsResponse): ?string
    {
        if ($jmsResponse instanceof ArrayIterator) {
            return 'array';
        }

        return null;
    }
}
