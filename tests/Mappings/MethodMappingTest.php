<?php

namespace Maiorano\ObjectHydrator\Tests\Mappings;

use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use Maiorano\ObjectHydrator\Mappings\HydrationMappingInterface;
use Maiorano\ObjectHydrator\Mappings\MethodMapping;
use Maiorano\ObjectHydrator\Tests\Fixtures\MethodsFixture;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionParameter;

class MethodMappingTest extends TestCase
{
    private HydrationMappingInterface $mapping;
    private ReflectionMethod $reflector;

    public function setUp(): void
    {
        $this->reflector = $this->createMock(ReflectionMethod::class);
        $this->mapping = new MethodMapping($this->reflector, new HydrationKey('testKey'));
    }

    public function testGetType()
    {
        $parameter = $this->createMock(ReflectionParameter::class);
        $this->reflector->expects($this->once())
            ->method('getParameters')
            ->willReturn([$parameter]);
        $parameter->expects($this->once())
            ->method('getType')
            ->willReturn(null);

        $this->assertNull($this->mapping->getType());
    }

    public function testSetValue()
    {
        $fixture = new MethodsFixture;
        $this->reflector->expects($this->once())
            ->method('invoke')
            ->with(
                $this->identicalTo($fixture),
                $this->equalTo('test')
            );

        $this->mapping->setValue($fixture, 'test');
    }

    public function testGetKey()
    {
        $this->assertEquals('testKey', $this->mapping->getKey());
    }
}
