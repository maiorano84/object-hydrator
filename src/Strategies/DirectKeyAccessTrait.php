<?php

namespace Maiorano\ObjectHydrator\Strategies;

use Maiorano\ObjectHydrator\Mappings\HydrationMappingInterface;

trait DirectKeyAccessTrait
{
    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasMatchingKey(string $key): bool
    {
        return isset($this->mappings[$key]);
    }

    /**
     * @param string $key
     *
     * @return HydrationMappingInterface
     */
    public function getMapping(string $key): HydrationMappingInterface
    {
        return $this->mappings[$key];
    }
}
