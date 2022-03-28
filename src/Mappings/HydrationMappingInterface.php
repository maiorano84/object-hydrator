<?php

namespace Maiorano\ObjectHydrator\Mappings;

use ReflectionNamedType;

interface HydrationMappingInterface
{
    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return ?ReflectionNamedType
     */
    public function getType(): ?ReflectionNamedType;

    /**
     * @param object $object
     * @param mixed $value
     * @return void
     */
    public function setValue(object $object, mixed $value): void;
}