<?php

namespace Maiorano\ObjectHydrator;

use Generator;
use Maiorano\ObjectHydrator\Attributes\HydrationStrategy;
use Maiorano\ObjectHydrator\Strategies\HydrationStrategyInterface;
use Maiorano\ObjectHydrator\Strategies\Reflection\MethodsStrategy;
use Maiorano\ObjectHydrator\Strategies\Reflection\PropertiesStrategy;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use stdClass;

final class Hydrator implements HydratorInterface
{
    /**
     * @param string|object $object
     * @param array $input
     * @return object
     * @throws ReflectionException
     */
    public function hydrate(string|object $object, array $input): object
    {
        $reflect = new ReflectionClass($object);
        if (is_string($object)) {
            $object = $reflect->newInstanceWithoutConstructor();
        }

        $strategies = iterator_to_array($this->generateStrategies($reflect));
        foreach ($input as $key => $value) {
            if ($strategy = $this->locateStrategy($strategies, $object, $key)) {
                $this->executeStrategy($strategy, $object, $key, $value);
            }
        }

        return $object;
    }

    /**
     * @param ReflectionClass $reflect
     * @return Generator
     */
    private function generateStrategies(ReflectionClass $reflect): Generator
    {
        $attributes = $reflect->getAttributes(HydrationStrategy::class);
        yield from $attributes
            ? $this->loadFromAttributes($attributes)
            : $this->loadFromDefaults();
    }

    /**
     * @param ReflectionAttribute[] $attributes
     * @return Generator
     */
    private function loadFromAttributes(array $attributes): Generator
    {
        foreach ($attributes as $attribute) {
            $key = $attribute->newInstance();
            $name = $key->getStrategy();
            yield new $name(...$key->getArgs());
        }
    }

    /**
     * @return Generator
     */
    private function loadFromDefaults(): Generator
    {
        yield new PropertiesStrategy;
        yield new MethodsStrategy;
    }

    /**
     * @param HydrationStrategyInterface[] $strategies
     * @param object $object
     * @param string $key
     * @return HydrationStrategyInterface|null
     */
    private function locateStrategy(array $strategies, object $object, string $key): ?HydrationStrategyInterface
    {
        foreach ($strategies as $strategy) {
            $strategy->initialize($object);
            if ($strategy->hasMatchingKey($key)) {
                return $strategy;
            }
        }
        return null;
    }

    /**
     * @param HydrationStrategyInterface $strategy
     * @param object $object
     * @param string $key
     * @param mixed $value
     * @return void
     * @throws ReflectionException
     */
    private function executeStrategy(HydrationStrategyInterface $strategy, object $object, string $key, mixed $value): void
    {
        $mapping = $strategy->getMapping($key);
        if (!$strategy->isRecursive($key, $value)) {
            $mapping->setValue($object, $value);
            return;
        }

        $type = $mapping->getType()?->getName() ?? stdClass::class;
        $mapping->setValue($object, $this->hydrate($type, $value));
    }
}