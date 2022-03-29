<?php

namespace Maiorano\ObjectHydrator\Tests\Fixtures;

use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use Maiorano\ObjectHydrator\Attributes\HydrationStrategy;
use Maiorano\ObjectHydrator\Strategies\Reflection\MethodsStrategy;

#[HydrationStrategy(MethodsStrategy::class)]
class MethodsFixture
{
    private string $testString;
    private string $testAttribute;
    private ?InnerFixture $innerFixture;

    public function getTestString(): string
    {
        return $this->testString;
    }

    public function setTestString(string $value)
    {
        $this->testString = $value;
    }

    public function getTestAttribute(): string
    {
        return $this->testAttribute;
    }

    #[HydrationKey('explicitAttribute')]
    public function setTestAttribute(string $value)
    {
        $this->testAttribute = $value;
    }

    public function getInnerFixture(): InnerFixture
    {
        return $this->innerFixture;
    }

    public function setInnerFixture(?InnerFixture $innerFixture)
    {
        $this->innerFixture = $innerFixture;
    }
}
