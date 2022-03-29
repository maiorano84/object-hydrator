<?php

namespace Maiorano\ObjectHydrator\Tests\Fixtures\Examples;

use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use Maiorano\ObjectHydrator\Attributes\HydrationStrategy;
use Maiorano\ObjectHydrator\Strategies\Reflection\PropertiesStrategy;
use ReflectionProperty;

#[HydrationStrategy(PropertiesStrategy::class, ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE)]
class UserAvatar
{
    public string $name;
    #[HydrationKey('_id')]
    private int $id;
    private string $filePath;

    public function getUrl(): string
    {
        return sprintf('https://mydomain.com/images/%s/%d-%s', $this->filePath, $this->id, $this->name);
    }
}
