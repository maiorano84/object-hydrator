<?php

namespace Maiorano\ObjectHydrator\Mappings;

use JetBrains\PhpStorm\Pure;
use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use ReflectionNamedType;
use ReflectionProperty;

class PropertyMapping implements HydrationMappingInterface
{
    /**
     * @var ReflectionProperty
     */
    private ReflectionProperty $reflector;
    /**
     * @var HydrationKey
     */
    private HydrationKey $key;

    /**
     * @param ReflectionProperty $reflector
     * @param HydrationKey       $key
     */
    public function __construct(ReflectionProperty $reflector, HydrationKey $key)
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
        return $this->reflector->getType();
    }

    /**
     * @param object $object
     * @param mixed  $value
     *
     * @return void
     */
    public function setValue(object $object, mixed $value): void
    {
        $this->reflector->setValue($object, $value);
    }
}
