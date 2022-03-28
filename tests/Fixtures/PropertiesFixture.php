<?php

namespace Maiorano\ObjectHydrator\Tests\Fixtures;

use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use Maiorano\ObjectHydrator\Attributes\HydrationStrategy;
use Maiorano\ObjectHydrator\Strategies\Reflection\PropertiesStrategy;

#[HydrationStrategy(PropertiesStrategy::class)]
class PropertiesFixture
{
    #[HydrationKey('explicitAttribute')]
    public string $testAttribute;
    public string $testString;
    public ?InnerFixture $innerFixture;
}
