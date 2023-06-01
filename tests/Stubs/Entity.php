<?php

declare(strict_types=1);

namespace WayOfDev\Serializer\Tests\Stubs;

use JMS\Serializer\Annotation;

final class Entity
{
    #[Annotation\Type('array<WayOfDev\Serializer\Tests\Stubs\Item>')]
    public ?array $items = null;

    #[Annotation\Type('integer')]
    private int $amount = 777;

    #[Annotation\Type('string')]
    private string $text = 'Some String';

    public function items(): ?array
    {
        return $this->items;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function text(): string
    {
        return $this->text;
    }
}
