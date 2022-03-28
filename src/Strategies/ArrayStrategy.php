<?php

namespace Maiorano\ObjectHydrator\Strategies;

use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use Maiorano\ObjectHydrator\Mappings\HydrationMappingInterface;
use Maiorano\ObjectHydrator\Mappings\MethodMapping;
use Maiorano\ObjectHydrator\Mappings\PropertyMapping;
use ReflectionMethod;
use ReflectionProperty;

class ArrayStrategy implements HydrationStrategyInterface
{
    use RecursiveCheckTrait;

    private array $config;
    private array $mappings;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function initialize(object $object): void
    {
        $this->mappings = iterator_to_array($this->generateMappings($object));
    }

    private function generateMappings(object $object)
    {
        foreach ($this->config as $key => $value) {
            if (is_bool($value) && property_exists($object, $key)) {
                $property = new ReflectionProperty($object, $key);
                yield $key => new PropertyMapping($property, new HydrationKey($key));
            } else if (is_string($value) && method_exists($object, $value)) {
                $method = new ReflectionMethod($object, $value);
                yield $key => new MethodMapping($method, new HydrationKey($key));
            }
        }
    }

    public function hasMatchingKey(string $key): bool
    {
        return isset($this->mappings[$key]);
    }

    public function getMapping(string $key): HydrationMappingInterface
    {
        return $this->mappings[$key];
    }
}