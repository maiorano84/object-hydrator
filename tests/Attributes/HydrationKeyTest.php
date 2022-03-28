<?php

namespace Maiorano\ObjectHydrator\Tests\Attributes;

use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use PHPUnit\Framework\TestCase;

class HydrationKeyTest extends TestCase
{
    private HydrationKey $key;

    public function setUp(): void
    {
        $this->key = new HydrationKey('test');
    }

    public function testGetKey()
    {
        $this->assertEquals('test', $this->key->getKey());
    }
}
