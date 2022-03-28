<?php

namespace Maiorano\ObjectHydrator\Tests\Strategies\Reflection;

use Maiorano\ObjectHydrator\Mappings\HydrationMappingInterface;
use Maiorano\ObjectHydrator\Strategies\HydrationStrategyInterface;
use Maiorano\ObjectHydrator\Strategies\Reflection\PropertiesStrategy;
use Maiorano\ObjectHydrator\Tests\Fixtures\PropertiesFixture;
use PHPUnit\Framework\TestCase;

class PropertiesStrategyTest extends TestCase
{
    private HydrationStrategyInterface $strategy;

    public function setUp(): void
    {
        $this->strategy = new PropertiesStrategy;
        $this->strategy->initialize(new PropertiesFixture);
    }

    public function testHasMatchingKey()
    {
        $this->assertTrue($this->strategy->hasMatchingKey('explicitAttribute'));
        $this->assertTrue($this->strategy->hasMatchingKey('testString'));
        $this->assertFalse($this->strategy->hasMatchingKey('testAttribute'));
    }

    public function testIsRecursive()
    {
        $this->assertTrue($this->strategy->isRecursive('innerFixture', []));
        $this->assertFalse($this->strategy->isRecursive('innerFixture', null));
    }

    public function testGetMapping()
    {
        $this->assertInstanceOf(HydrationMappingInterface::class, $this->strategy->getMapping('explicitAttribute'));
    }
}
