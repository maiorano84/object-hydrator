<?php

namespace Maiorano\ObjectHydrator\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class HydrationStrategy
{
    /**
     * @var string
     */
    private string $strategy;
    /**
     * @var array
     */
    private array $args;

    /**
     * @param string $strategy
     * @param mixed ...$args
     */
    public function __construct(string $strategy, mixed ...$args)
    {
        $this->strategy = $strategy;
        $this->args = $args;
    }

    /**
     * @return string
     */
    public function getStrategy(): string
    {
        return $this->strategy;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }
}