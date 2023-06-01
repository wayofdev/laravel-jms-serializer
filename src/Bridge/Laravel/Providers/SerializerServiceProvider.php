<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Bridge\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use WayOfDev\Serializer\Config;
use WayOfDev\Serializer\ResponseFactory;
use WayOfDev\Serializer\SerializerFactory;

final class SerializerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../../../config/serializer.php' => config_path('serializer.php'),
            ], 'config');

            $this->registerConsoleCommands();
        }
    }

    public function register(): void
    {
        // @phpstan-ignore-next-line
        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../../../../config/serializer.php', 'serializer');
        }

        $config = Config::fromArray([
            'serialize_null' => config('serializer.serialize_null', true),
            'cache_dir' => storage_path('framework/cache'),
            'serialize_format' => config('serializer.serialize_format', Config::SERIALIZE_FORMAT_JSON),
            'debug' => config('serializer.debug', false),
        ]);

        $this->app->singleton(ResponseFactory::class, static function () use ($config): ResponseFactory {
            return new ResponseFactory((new SerializerFactory())->getSerializer($config), $config);
        });
    }

    private function registerConsoleCommands(): void
    {
        $this->commands([]);
    }
}
