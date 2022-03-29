<?php

namespace Maiorano\ObjectHydrator\Strategies;

use Generator;
use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use Maiorano\ObjectHydrator\Mappings\HydrationMappingInterface;
use Maiorano\ObjectHydrator\Mappings\MethodMapping;
use Maiorano\ObjectHydrator\Mappings\PropertyMapping;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

class ArrayStrategy implements HydrationStrategyInterface
{
    use DirectKeyAccessTrait;
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
     *
     * @return void
     */
    public function initialize(object $object): void
    {
        $this->mappings = iterator_to_array($this->generateMappings($object));
    }

    /**
     * @param object $object
     *
     * @throws ReflectionException
     *
     * @return Generator
     */
    private function generateMappings(object $object): Generator
    {
        foreach ($this->config as $key => $value) {
            $k = $value === true ? $key : $value;
            $hasProperty = property_exists($object, $k);
            $hasMethod = method_exists($object, $k);
            if ($hasProperty || $hasMethod) {
                $hydrationKey = new HydrationKey($k);
                yield $k => $hasProperty
                    ? new PropertyMapping(new ReflectionProperty($object, $value), $hydrationKey)
                    : new MethodMapping(new ReflectionMethod($object, $value), $hydrationKey);
            }
        }
    }
}
