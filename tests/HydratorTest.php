<?php

namespace Maiorano\ObjectHydrator\Tests;

use Maiorano\ObjectHydrator\Hydrator;
use Maiorano\ObjectHydrator\HydratorInterface;
use Maiorano\ObjectHydrator\Tests\Fixtures\ArrayFixture;
use Maiorano\ObjectHydrator\Tests\Fixtures\MethodsFixture;
use Maiorano\ObjectHydrator\Tests\Fixtures\PropertiesFixture;
use PHPUnit\Framework\TestCase;

class HydratorTest extends TestCase
{
    private HydratorInterface $hydrator;

    public function setUp(): void
    {
        $this->hydrator = new Hydrator();
    }

    public function testHydrateProperties()
    {
        /**
         * @var PropertiesFixture $hydrated
         */
        $hydrated = $this->hydrator->hydrate(new PropertiesFixture(), [
            'unset'             => 'unset',
            'explicitAttribute' => 'hello',
            'testString'        => 'world',
            'innerFixture'      => [
                'foo' => 'hello',
                'bar' => 'world',
            ],
        ]);

        $this->assertEquals('hello', $hydrated->testAttribute);
        $this->assertEquals('world', $hydrated->testString);
        $this->assertEquals('hello', $hydrated->innerFixture->foo);
        $this->assertEquals('world', $hydrated->innerFixture->bar);
    }

    public function testHydrateMethods()
    {
        /**
         * @var MethodsFixture $hydrated
         */
        $hydrated = $this->hydrator->hydrate(new MethodsFixture(), [
            'unset'             => 'unset',
            'explicitAttribute' => 'hello',
            'testString'        => 'world',
            'innerFixture'      => [
                'foo' => 'hello',
                'bar' => 'world',
            ],
        ]);

        $this->assertEquals('hello', $hydrated->getTestAttribute());
        $this->assertEquals('world', $hydrated->getTestString());
        $this->assertEquals('hello', $hydrated->getInnerFixture()->foo);
        $this->assertEquals('world', $hydrated->getInnerFixture()->bar);
    }

    public function testArrayConfiguration()
    {
        /**
         * @var ArrayFixture $hydrated
         */
        $hydrated = $this->hydrator->hydrate(new ArrayFixture(), [
            'unset'        => 2,
            'namedProp'    => 'foo',
            'protected'    => 'bar',
            'private'      => 'baz',
            'innerFixture' => [
                'foo' => 'hello',
                'bar' => 'world',
            ],
        ]);

        $this->assertEquals(1, $hydrated->unset);
        $this->assertEquals('foo', $hydrated->public);
        $this->assertEquals('bar', $hydrated->getProtected());
        $this->assertEquals('baz', $hydrated->getPrivate());
        $this->assertEquals('hello', $hydrated->getInnerFixture()->foo);
        $this->assertEquals('world', $hydrated->getInnerFixture()->bar);
    }

    public function testHydrateNullable()
    {
        /**
         * @var PropertiesFixture $hydrated
         */
        $hydrated = $this->hydrator->hydrate(new PropertiesFixture(), [
            'innerFixture' => null,
        ]);

        $this->assertNull($hydrated->innerFixture);
    }
}
