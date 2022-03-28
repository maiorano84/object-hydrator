<?php

namespace Maiorano\ObjectHydrator\Strategies;

use Maiorano\ObjectHydrator\Mappings\HydrationMappingInterface;

/**
 * Defines the business logic for determining a Key => Mapping association
 */
interface HydrationStrategyInterface
{
    /**
     * Used to perform any initial introspection on a given object
     * Default strategies will use this method to pre-populate a key mapping
     *
     * @param object $object
     * @return void
     */
    public function initialize(object $object): void;

    /**
     * Determines if a Strategy can handle a particular keys
     *
     * @param string $key
     * @return bool
     */
    public function hasMatchingKey(string $key): bool;

    /**
     * Determines if the hydrator should recurse through this value
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function isRecursive(string $key, mixed $value): bool;

    /**
     * Returns the underlying mapping for a given key
     *
     * @param string $key
     * @return HydrationMappingInterface
     */
    public function getMapping(string $key): HydrationMappingInterface;
}