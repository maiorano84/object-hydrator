<?php

namespace Maiorano\ObjectHydrator\Strategies\Reflection;

use Generator;
use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use Maiorano\ObjectHydrator\Mappings\MethodMapping;
use Maiorano\ObjectHydrator\Strategies\DirectKeyAccessTrait;
use Maiorano\ObjectHydrator\Strategies\HydrationStrategyInterface;
use ReflectionClass;
use ReflectionMethod;

final class MethodsStrategy implements HydrationStrategyInterface
{
    use DirectKeyAccessTrait;

    /**
     * @var int|null
     */
    private ?int $methodTypes;
    /**
     * @var string
     */
    private string $prefix;
    /**
     * @var ReflectionClass
     */
    private ReflectionClass $reflectionClass;
    /**
     * @var MethodMapping[]
     */
    private array $mappings;

    /**
     * @param int|null $methodTypes
     * @param string $prefix
     */
    public function __construct(?int $methodTypes = ReflectionMethod::IS_PUBLIC, string $prefix = 'set')
    {
        $this->methodTypes = $methodTypes;
        $this->prefix = $prefix;
    }

    /**
     * @param object $object
     *
     * @return void
     */
    public function initialize(object $object): void
    {
        $this->reflectionClass = new ReflectionClass($object);
        $this->mappings = iterator_to_array($this->generateKeyMap());
    }

    /**
     * @return Generator
     */
    private function generateKeyMap(): Generator
    {
        foreach ($this->reflectionClass->getMethods($this->methodTypes) as $reflector) {
            $attributes = $reflector->getAttributes(HydrationKey::class);
            yield from $attributes
                ? $this->generateKeysFromAttributes($reflector, $attributes)
                : $this->generateKeysFromNames($reflector);
        }
    }

    /**
     * @param ReflectionMethod $method
     * @param array $attributes
     *
     * @return Generator
     */
    private function generateKeysFromAttributes(ReflectionMethod $method, array $attributes): Generator
    {
        foreach ($attributes as $attribute) {
            $mapping = new MethodMapping($method, $attribute->newInstance());
            yield $mapping->getKey() => $mapping;
        }
    }

    /**
     * @param ReflectionMethod $method
     *
     * @return Generator
     */
    private function generateKeysFromNames(ReflectionMethod $method): Generator
    {
        $maybeProp = preg_match("/^{$this->prefix}(.*)/i", $method->getName(), $matches);
        if ($maybeProp && isset($matches[1])) {
            yield from $this->checkPropertyNames($method, $this->cleanName($matches[1]));
        }
    }

    /**
     * @param ReflectionMethod $method
     * @param string $name
     *
     * @return Generator
     */
    private function checkPropertyNames(ReflectionMethod $method, string $name): Generator
    {
        foreach ($this->reflectionClass->getProperties() as $property) {
            if ($name === $this->cleanName($property->getName())) {
                $mapping = new MethodMapping($method, new HydrationKey($name));
                yield $mapping->getKey() => $mapping;
                break;
            }
        }
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function cleanName(string $name): string
    {
        return lcfirst(preg_replace('/[^a-zA-Z]/', '', $name));
    }
}
