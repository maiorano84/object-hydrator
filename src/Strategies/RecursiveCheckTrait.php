<?php

namespace Maiorano\ObjectHydrator\Strategies;

use ReflectionType;

trait RecursiveCheckTrait
{
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return bool
     */
    public function isRecursive(string $key, mixed $value): bool
    {
        return $this->checkTypeRecursion($this->getMapping($key)->getType(), $value);
    }

    /**
     * @param ReflectionType|null $type
     * @param mixed $value
     *
     * @return bool
     */
    private function checkTypeRecursion(?ReflectionType $type, mixed $value): bool
    {
        if (!$type) {
            return false;
        }

        $allowsNull = $type->allowsNull() ?? true;
        if ($allowsNull && is_null($value)) {
            return false;
        }

        return !is_scalar($value) && !$type->isBuiltin();
    }
}
