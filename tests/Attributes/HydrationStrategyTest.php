<?php

namespace Maiorano\ObjectHydrator\Tests\Attributes;

use Maiorano\ObjectHydrator\Attributes\HydrationStrategy;
use PHPUnit\Framework\TestCase;

class HydrationStrategyTest extends TestCase
{
    private HydrationStrategy $strategy;

    public function setUp(): void
    {
        $this->strategy = new HydrationStrategy('test', 'arg', 1, [2, 3]);
    }

    public function testGetArgs()
    {
        $this->assertCount(3, $this->strategy->getArgs());
    }

    public function testGetStrategy()
    {
        $this->assertEquals('test', $this->strategy->getStrategy());
    }
}
