<?php

namespace Maiorano\ObjectHydrator\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class HydrationStrategy
{
    private string $strategy;
    private array $args;

    public function __construct(string $strategy, mixed ...$args)
    {
        $this->strategy = $strategy;
        $this->args = $args;
    }

    public function getStrategy(): string
    {
        return $this->strategy;
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}