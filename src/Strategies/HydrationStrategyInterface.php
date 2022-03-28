<?php

namespace Maiorano\ObjectHydrator\Strategies;

use Maiorano\ObjectHydrator\Mappings\HydrationMappingInterface;

interface HydrationStrategyInterface
{
    /**
     * @param object $object
     * @return void
     */
    public function initialize(object $object): void;

    /**
     * @param string $key
     * @return bool
     */
    public function hasMatchingKey(string $key): bool;

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function isRecursive(string $key, mixed $value): bool;

    /**
     * @param string $key
     * @return HydrationMappingInterface
     */
    public function getMapping(string $key): HydrationMappingInterface;
}