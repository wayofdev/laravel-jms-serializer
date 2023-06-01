<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Tests\Stubs;

use JMS\Serializer\Annotation;

final class Item
{
    #[Annotation\Type('string')]
    private string $key = 'magic_number';

    #[Annotation\Type('integer')]
    private int $value = 12;

    public function key(): string
    {
        return $this->key;
    }

    public function value(): int
    {
        return $this->value;
    }
}
