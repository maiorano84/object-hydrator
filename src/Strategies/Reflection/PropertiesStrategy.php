<?php

namespace Maiorano\ObjectHydrator\Strategies\Reflection;

use Generator;
use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use Maiorano\ObjectHydrator\Mappings\PropertyMapping;
use Maiorano\ObjectHydrator\Strategies\HydrationStrategyInterface;
use Maiorano\ObjectHydrator\Strategies\RecursiveCheckTrait;
use ReflectionClass;
use ReflectionProperty;

final class PropertiesStrategy implements HydrationStrategyInterface
{
    use RecursiveCheckTrait;

    /**
     * @var int
     */
    private int $propertyTypes;
    /**
     * @var ReflectionClass
     */
    private ReflectionClass $reflectionClass;
    /**
     * @var PropertyMapping[]
     */
    private array $properties;

    /**
     * @param int $propertyTypes
     */
    public function __construct(int $propertyTypes = ReflectionProperty::IS_PUBLIC)
    {
        $this->propertyTypes = $propertyTypes;
    }

    /**
     * @param object $object
     * @return void
     */
    public function initialize(object $object): void
    {
        $this->reflectionClass = new ReflectionClass($object);
        $this->properties = iterator_to_array($this->generateKeyMap());
    }

    /**
     * @return Generator
     */
    private function generateKeyMap(): Generator
    {
        foreach ($this->reflectionClass->getProperties($this->propertyTypes) as $property) {
            $attributes = $property->getAttributes(HydrationKey::class);
            yield from $attributes
                ? $this->generateKeysFromAttributes($property, $attributes)
                : $this->generateKeysFromNames($property);
        }
    }

    /**
     * @param ReflectionProperty $property
     * @param array $attributes
     * @return Generator
     */
    private function generateKeysFromAttributes(ReflectionProperty $property, array $attributes): Generator
    {
        foreach ($attributes as $attribute) {
            $mapping = new PropertyMapping($property, $attribute->newInstance());
            yield $mapping->getKey() => $mapping;
        }
    }

    /**
     * @param ReflectionProperty $property
     * @return Generator
     */
    private function generateKeysFromNames(ReflectionProperty $property): Generator
    {
        $mapping = new PropertyMapping($property, new HydrationKey($property->getName()));
        yield $mapping->getKey() => $mapping;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasMatchingKey(string $key): bool
    {
        return isset($this->properties[$key]);
    }

    /**
     * @param string $key
     * @return PropertyMapping
     */
    public function getMapping(string $key): PropertyMapping
    {
        return $this->properties[$key];
    }
}