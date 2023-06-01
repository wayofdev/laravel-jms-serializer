<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Contracts;

interface ConfigRepository
{
    public function cacheDir(): string;

    public function serializeNull(): bool;

    public function serializeFormat(): string;

    public function debug(): bool;
}
