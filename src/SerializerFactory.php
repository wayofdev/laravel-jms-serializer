<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use WayOfDev\Serializer\Contracts\ConfigRepository;

final class SerializerFactory
{
    public function getSerializer(ConfigRepository $config): SerializerInterface
    {
        $builder = new SerializerBuilder();

        $builder::create()
            ->setPropertyNamingStrategy(
                new SerializedNameAnnotationStrategy(
                    new IdenticalPropertyNamingStrategy()
                )
            )
            ->addDefaultHandlers()
            ->addDefaultListeners()
            ->setSerializationContextFactory(static function () use ($config): SerializationContext {
                return SerializationContext::create()
                    ->setSerializeNull($config->serializeNull())
                ;
            })
            ->setCacheDir($config->cacheDir())->setDebug($config->debug())->build();

        return $builder->build();
    }
}
