<?php

namespace Maiorano\ObjectHydrator\Tests\Fixtures\Examples;

use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use Maiorano\ObjectHydrator\Attributes\HydrationStrategy;
use Maiorano\ObjectHydrator\Strategies\ArrayStrategy;
use Maiorano\ObjectHydrator\Strategies\Reflection\MethodsStrategy;
use Maiorano\ObjectHydrator\Strategies\Reflection\PropertiesStrategy;

#[HydrationStrategy(ArrayStrategy::class, ['_id' => 'id', '_email' => 'email'])]
#[HydrationStrategy(PropertiesStrategy::class)]
#[HydrationStrategy(MethodsStrategy::class)]
class ComplexUser
{
    public string $username;
    private int $id;
    private string $email;
    private string $password;
    private UserAvatar $avatar;

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUserPassword(): string
    {
        return $this->password;
    }

    #[HydrationKey('_password')]
    public function setUserPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getAvatar(): UserAvatar
    {
        return $this->avatar;
    }

    public function setAvatar(UserAvatar $avatar): void
    {
        $this->avatar = $avatar;
    }
}
