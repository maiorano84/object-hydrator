<?php

namespace Maiorano\ObjectHydrator\Tests\Fixtures\Examples;

class SimpleUser
{
    public string $username;
    private string $password;

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
}