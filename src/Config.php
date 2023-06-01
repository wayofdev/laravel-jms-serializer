<?php

declare(strict_types=1);

namespace WayOfDev\Serializer;

use WayOfDev\Serializer\Contracts\ConfigRepository;
use WayOfDev\Serializer\Exceptions\MissingRequiredAttributes;

use function array_diff;
use function array_keys;
use function implode;
use function sprintf;

final class Config implements ConfigRepository
{
    public const SERIALIZE_FORMAT_JSON = 'json';

    private const CACHE_DIR = '/serializer/';

    private const REQUIRED_FIELDS = [
        'serialize_null',
        'cache_dir',
        'serialize_format',
        'debug',
    ];

    private string $cacheDir;

    private bool $serializeNull;

    private string $serializeFormat;

    private bool $debug;

    public static function fromArray(array $config): self
    {
        $missingAttributes = array_diff(self::REQUIRED_FIELDS, array_keys($config));

        if ([] !== $missingAttributes) {
            throw MissingRequiredAttributes::fromArray(
                implode(',', $missingAttributes)
            );
        }

        return new self(
            $config['cache_dir'],
            $config['serialize_null'],
            $config['serialize_format'],
            $config['debug']
        );
    }

    public function __construct(string $cacheDir, bool $serializeNull, string $serializeFormat, bool $debug)
    {
        $this->cacheDir = sprintf('%s%s', $cacheDir, self::CACHE_DIR);
        $this->serializeNull = $serializeNull;
        $this->serializeFormat = $serializeFormat;
        $this->debug = $debug;
    }

    public function cacheDir(): string
    {
        return $this->cacheDir;
    }

    public function serializeNull(): bool
    {
        return $this->serializeNull;
    }

    public function serializeFormat(): string
    {
        return $this->serializeFormat;
    }

    public function debug(): bool
    {
        return $this->debug;
    }
}
