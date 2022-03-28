<?php

namespace Maiorano\ObjectHydrator\Strategies\Reflection;

use Generator;
use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use ReflectionMethod;
use ReflectionProperty;

trait AttributeReflectionTrait
{
    /**
     * @param ReflectionProperty[]|ReflectionMethod[] $reflectors
     * @return Generator
     */
    private function generateKeyMap(array $reflectors): Generator
    {
        foreach ($reflectors as $property) {
            $attributes = $property->getAttributes(HydrationKey::class);
            yield from $attributes
                ? $this->generateKeysFromAttributes($property, $attributes)
                : $this->generateKeysFromNames($property);
        }
    }
}