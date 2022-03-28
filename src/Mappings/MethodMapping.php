<?php

namespace Maiorano\ObjectHydrator\Mappings;

use JetBrains\PhpStorm\Pure;
use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;

class MethodMapping implements HydrationMappingInterface
{
    /**
     * @var ReflectionMethod
     */
    private ReflectionMethod $reflector;
    /**
     * @var HydrationKey
     */
    private HydrationKey $key;

    /**
     * @param ReflectionMethod $reflector
     * @param HydrationKey $key
     */
    public function __construct(ReflectionMethod $reflector, HydrationKey $key)
    {
        $this->reflector = $reflector;
        $this->key = $key;
    }

    /**
     * @return string
     */
    #[Pure]
    public function getKey(): string
    {
        return $this->key->getKey();
    }

    /**
     * @return ?ReflectionNamedType
     */
    #[Pure]
    public function getType(): ?ReflectionNamedType
    {
        $parameters = $this->reflector->getParameters();
        return $parameters[0]?->getType();
    }

    /**
     * @param object $object
     * @param mixed $value
     * @return void
     * @throws ReflectionException
     */
    public function setValue(object $object, mixed $value): void
    {
        $this->reflector->invoke($object, $value);
    }
}