<?php

namespace Maiorano\ObjectHydrator\Tests\Strategies;

use Maiorano\ObjectHydrator\Mappings\HydrationMappingInterface;
use Maiorano\ObjectHydrator\Strategies\ArrayStrategy;
use Maiorano\ObjectHydrator\Strategies\HydrationStrategyInterface;
use Maiorano\ObjectHydrator\Tests\Fixtures\ArrayFixture;
use PHPUnit\Framework\TestCase;

class ArrayStrategyTest extends TestCase
{
    private HydrationStrategyInterface $strategy;

    public function setUp(): void
    {
        $this->strategy = new ArrayStrategy([
            'private'      => true,
            'protected'    => true,
            'namedProp'    => 'public',
            'innerFixture' => 'setInnerFixture',
        ]);
        $this->strategy->initialize(new ArrayFixture());
    }

    public function testGetMapping()
    {
        $this->assertInstanceOf(HydrationMappingInterface::class, $this->strategy->getMapping('private'));
    }

    public function testHasMatchingKey()
    {
        $this->assertTrue($this->strategy->hasMatchingKey('private'));
        $this->assertTrue($this->strategy->hasMatchingKey('protected'));
        $this->assertTrue($this->strategy->hasMatchingKey('namedProp'));
        $this->assertFalse($this->strategy->hasMatchingKey('public'));
        $this->assertFalse($this->strategy->hasMatchingKey('unset'));
    }

    public function testIsRecursive()
    {
        $this->assertTrue($this->strategy->isRecursive('innerFixture', []));
    }
}
