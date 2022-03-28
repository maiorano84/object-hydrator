<?php

namespace Maiorano\ObjectHydrator;

interface HydratorInterface
{
    /**
     * @param string|object $object
     * @param array $input
     * @return object
     */
    public function hydrate(string|object $object, array $input): object;
}