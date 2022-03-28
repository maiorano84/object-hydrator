<?php

namespace Maiorano\ObjectHydrator\Strategies;

use Generator;
use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use Maiorano\ObjectHydrator\Mappings\HydrationMappingInterface;
use Maiorano\ObjectHydrator\Mappings\MethodMapping;
use Maiorano\ObjectHydrator\Mappings\PropertyMapping;
use ReflectionMethod;
use ReflectionProperty;

class ArrayStrategy implements HydrationStrategyInterface
{
    use RecursiveCheckTrait;

    /**
     * @var array
     */
    private array $config;
    /**
     * @var HydrationMappingInterface[]
     */
    private array $mappings;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param object $object
     * @return void
     */
    public function initialize(object $object): void
    {
        $this->mappings = iterator_to_array($this->generateMappings($object));
    }

    /**
     * @param object $object
     * @return Generator
     */
    private function generateMappings(object $object): Generator
    {
        foreach ($this->config as $key => $value) {
            if ($value === true && property_exists($object, $key)) {
                yield $key => $this->createPropertyMapping(new ReflectionProperty($object, $key), $key);
            } else if (is_string($value) && property_exists($object, $value)) {
                yield $key => $this->createPropertyMapping(new ReflectionProperty($object, $value), $key);
            } else if (is_string($value) && method_exists($object, $value)) {
                yield $key => $this->createMethodMapping(new ReflectionMethod($object, $value), $key);
            }
        }
    }

    /**
     * @param ReflectionProperty $reflector
     * @param string $key
     * @return HydrationMappingInterface
     */
    private function createPropertyMapping(ReflectionProperty $reflector, string $key): HydrationMappingInterface
    {
        return new PropertyMapping($reflector, new HydrationKey($key));
    }

    /**
     * @param ReflectionMethod $reflector
     * @param string $key
     * @return HydrationMappingInterface
     */
    private function createMethodMapping(ReflectionMethod $reflector, string $key): HydrationMappingInterface
    {
        return new MethodMapping($reflector, new HydrationKey($key));
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasMatchingKey(string $key): bool
    {
        return isset($this->mappings[$key]);
    }

    /**
     * @param string $key
     * @return HydrationMappingInterface
     */
    public function getMapping(string $key): HydrationMappingInterface
    {
        return $this->mappings[$key];
    }
}