<?php

namespace Maiorano\ObjectHydrator\Tests\Strategies\Reflection;

use Maiorano\ObjectHydrator\Mappings\HydrationMappingInterface;
use Maiorano\ObjectHydrator\Strategies\HydrationStrategyInterface;
use Maiorano\ObjectHydrator\Strategies\Reflection\MethodsStrategy;
use Maiorano\ObjectHydrator\Tests\Fixtures\MethodsFixture;
use PHPUnit\Framework\TestCase;

class MethodsStrategyTest extends TestCase
{
    private HydrationStrategyInterface $strategy;

    public function setUp(): void
    {
        $this->strategy = new MethodsStrategy;
        $this->strategy->initialize(new MethodsFixture);
    }

    public function testHasMatchingKey()
    {
        $this->assertTrue($this->strategy->hasMatchingKey('explicitAttribute'));
        $this->assertTrue($this->strategy->hasMatchingKey('testString'));
        $this->assertTrue($this->strategy->hasMatchingKey('innerFixture'));
        $this->assertFalse($this->strategy->hasMatchingKey('testAttribute'));
    }

    public function testIsRecursive()
    {
        $this->assertTrue($this->strategy->isRecursive('innerFixture', null));
    }

    public function testGetMapping()
    {
        $this->assertInstanceOf(HydrationMappingInterface::class, $this->strategy->getMapping('explicitAttribute'));
    }
}
