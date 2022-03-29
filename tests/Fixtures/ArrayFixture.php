<?php

namespace Maiorano\ObjectHydrator\Tests\Fixtures;

use Maiorano\ObjectHydrator\Attributes\HydrationStrategy;
use Maiorano\ObjectHydrator\Strategies\ArrayStrategy;

#[HydrationStrategy(ArrayStrategy::class, [
    'private' => true,
    'protected' => true,
    'namedProp' => 'public',
    'innerFixture' => 'setInnerFixture',
])]
class ArrayFixture
{
    public int $unset = 1;
    public string $public;
    protected string $protected;
    private string $private;
    private InnerFixture $innerFixture;

    public function getProtected(): string
    {
        return $this->protected;
    }

    public function getPrivate(): string
    {
        return $this->private;
    }

    public function getInnerFixture(): InnerFixture
    {
        return $this->innerFixture;
    }

    public function setInnerFixture(InnerFixture $innerFixture)
    {
        $this->innerFixture = $innerFixture;
    }
}
