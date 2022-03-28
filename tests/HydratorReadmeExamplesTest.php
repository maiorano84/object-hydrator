<?php

namespace Maiorano\ObjectHydrator\Tests;

use Maiorano\ObjectHydrator\Hydrator;
use Maiorano\ObjectHydrator\HydratorInterface;
use Maiorano\ObjectHydrator\Tests\Fixtures\Examples\ComplexUser;
use Maiorano\ObjectHydrator\Tests\Fixtures\Examples\SimpleUser;
use PHPUnit\Framework\TestCase;

class HydratorReadmeExamplesTest extends TestCase
{
    private HydratorInterface $hydrator;

    public function setUp(): void
    {
        $this->hydrator = new Hydrator;
    }

    public function testHydrateSimpleUser()
    {
        /**
         * @var SimpleUser $user
         */
        $user = $this->hydrator->hydrate(SimpleUser::class, [
            'username' => 'maiorano84',
            'password' => 'secret',
        ]);

        $this->assertEquals('maiorano84', $user->username);
        $this->assertTrue(password_verify('secret', $user->getPassword()));
    }

    public function testHydrateComplexUser()
    {
        /**
         * @var ComplexUser $user
         */
        $user = $this->hydrator->hydrate(ComplexUser::class, [
            '_id' => 1,
            '_email' => 'maiorano84@gmail.com',
            '_password' => 'secret',
            'username' => 'maiorano84',
            'avatar' => [
                '_id' => 123,
                'name' => 'maiorano84.jpg',
                'filePath' => 'path/to/avatars'
            ],
        ]);

        $this->assertEquals(1, $user->getId());
        $this->assertEquals('maiorano84@gmail.com', $user->getEmail());
        $this->assertTrue(password_verify('secret', $user->getUserPassword()));
        $this->assertEquals('maiorano84', $user->username);
        $this->assertEquals('https://mydomain.com/images/path/to/avatars/123-maiorano84.jpg', $user->getAvatar()->getUrl());
    }
}
