<?php

namespace Maiorano\ObjectHydrator\Mappings;

use ReflectionNamedType;

/**
 * Defines an association between a Key and an object's Property or Method.
 */
interface HydrationMappingInterface
{
    /**
     * Retrieves the underlying key.
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Gets the Named Type for an associated Property or method
     * TODO: Consider handling Union Types, or other edge cases.
     *
     * @return ?ReflectionNamedType
     */
    public function getType(): ?ReflectionNamedType;

    /**
     * Sets the underlying property value or invokes the underlying
     * method on the object.
     *
     * @param object $object
     * @param mixed $value
     *
     * @return void
     */
    public function setValue(object $object, mixed $value): void;
}
