<?php

namespace Maiorano\ObjectHydrator\Tests\Mappings;

use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use Maiorano\ObjectHydrator\Mappings\HydrationMappingInterface;
use Maiorano\ObjectHydrator\Mappings\PropertyMapping;
use Maiorano\ObjectHydrator\Tests\Fixtures\PropertiesFixture;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class PropertyMappingTest extends TestCase
{
    private HydrationMappingInterface $mapping;
    private ReflectionProperty $reflector;

    public function setUp(): void
    {
        $this->reflector = $this->createMock(ReflectionProperty::class);
        $this->mapping = new PropertyMapping($this->reflector, new HydrationKey('testKey'));
    }

    public function testGetType()
    {
        $this->reflector->expects($this->once())
            ->method('getType')
            ->willReturn(null);

        $this->assertNull($this->mapping->getType());
    }

    public function testGetKey()
    {
        $this->assertEquals('testKey', $this->mapping->getKey());
    }

    public function testSetValue()
    {
        $fixture = new PropertiesFixture();
        $this->reflector->expects($this->once())
            ->method('setValue')
            ->with(
                $this->identicalTo($fixture),
                $this->equalTo('test')
            );

        $this->mapping->setValue($fixture, 'test');
    }
}
