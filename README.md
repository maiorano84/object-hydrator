# Object Hydrator

A small utility to streamline Object Hydration

[![Author](http://img.shields.io/badge/author-Matt%20Maiorano-blue.svg?style=flat-square)](https://mattmaiorano.com)
[![Latest Stable Version](https://poser.pugx.org/maiorano84/object-hydrator/v/stable)](https://packagist.org/packages/maiorano84/object-hydrator)
[![Total Downloads](https://poser.pugx.org/maiorano84/object-hydrator/downloads)](https://packagist.org/packages/maiorano84/object-hydrator)
[![License](https://poser.pugx.org/maiorano84/object-hydrator/license)](https://packagist.org/packages/maiorano84/object-hydrator)

## Requirements
Object Hydrator requires PHP 8.1 or greater.

## Composer
This package may be installed as a Composer dependency by running the following command:

`composer require maiorano84/object-hydrator`

If you would like to use the latest unstable version, you can run:

`composer require maiorano84/object-hydrator:dev-master`

## Usage
This is a small set of concepts to facilitate the orchestration of object hydration based on varying strategies.

There are 3 concepts that the default Hydration class uses in order to determine how a given input is applied to an
object:

* **Hydration Keys** - These are simple strings that are used to indicate the name of the input that is used. When an input key is matched to a Hydration Key, the value is passed through to the underlying mapping.
* **Hydration Mappings** - These are objects that store a reference to a given key and a reflection object that points to either a Property or a Method. When a mapping is matched by a given strategy, it will be passed a value that it can use to hydrate the object property.
* **Hydration Strategies** - These are objects that define the logic used to determine how an input key and value is mapped. Even further, these objects may also determine if a given key and value should be processed recursively by the Hydrator.

Hydration Keys and Hydration Strategies are both set up as Attributes, which may be used to decorate any given class structure marked for Hydration. In this way, you may customize how a given class is Hydrated.

Multiple Hydration Strategies may be applied to a single given class.

## Why?
A common task that many REST APIs and ORMs require is the ability to fill arbitary objects with structured data in bulk.
The criteria used for setting these values can vary wildly from class to class, and designating a unified interface by
which class hydration may occur predictably can prove to be challenging.

The simplest and most common approach would be to have each object implement a given interface that is responsible for
the hydration of that entity. The problem with this approach is that it violates the Single-Responsibility principle,
and adds extra business logic that the entity itself doesn't really need to be handling.

This removes much of the boilerplate code needed for handling custom hydration rules, while also providing a simple
interface by which classes may define their own rules and the order in which they're applied.

## Usage
Given a User Entity:

```php
namespace App;

class SimpleUser {
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
```

Hydration of a User entity can be as simple as the following:

```php
use App\SimpleUser;
use Maiorano\ObjectHydrator\Hydrator;

$hydrator = new Hydrator;
$user = $hydrator->hydrate(SimpleUser::class, [
    'username' => 'maiorano84',
    'password' => 'secret',
]);
print_r($user);
/*
App\SimpleUser Object
(
    [username] => maiorano84
    [password:App\SimpleUser:private] => <hashedPassword>
)
 */
```

By default, classes with no strategy defined will use a combination of the `Reflection\PropertiesStrategy` and
`Reflection\MethodsStrategy` in that order. The result will be all keys that match a public property name, and all keys
that match a property setter will be used to hydrate the entity. Note in the above example that the public `$username`
was hydrated with the value `maiorano84`, and the private `$password` was hydrated via the `setPassword` setter.

More complex structures may also be hydrated like so:

```php
namespace App;

use Maiorano\ObjectHydrator\Attributes\HydrationKey;
use Maiorano\ObjectHydrator\Attributes\HydrationStrategy;
use Maiorano\ObjectHydrator\Strategies\ArrayStrategy;
use Maiorano\ObjectHydrator\Strategies\Reflection\MethodsStrategy;
use Maiorano\ObjectHydrator\Strategies\Reflection\PropertiesStrategy;

#[HydrationStrategy(ArrayStrategy::class, ['_id' => 'id', '_email' => 'email'])]
#[HydrationStrategy(PropertiesStrategy::class)]
#[HydrationStrategy(MethodsStrategy::class)]
class ComplexUser {
    private int $id;
    public string $username;
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

#[HydrationStrategy(PropertiesStrategy::class, ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE)]
class UserAvatar {
    #[HydrationKey('_id')]
    private int $id;
    public string $name;
    private string $filePath;

    public function getUrl(): string
    {
        return sprintf('https://mydomain.com/images/%s/%d-%s', $this->filePath, $this->id, $this->name);
    }
}
```

There are a number of things going on here:

* An `ArrayStrategy` may be used to explicitly map certain input keys to properties or methods.
* We are chaining `PropertiesStrategy` and `MethodsStrategy` fallbacks to default to the appropriate public properties/setters behaviors for keys that aren't defined in the `ArrayStrategy`
* The `UserAvatar` class is allowing both private and public properties to be set for its implementation of the `PropertiesStrategy`
* `HydrationKey` is used to override the input keys in certain areas that would otherwise be difficult to determine through reflection

Hydration can be done similarly as before:

```php
use App\ComplexUser;
use Maiorano\ObjectHydrator\Hydrator;

$hydrator = new Hydrator;
$user = $hydrator->hydrate(SimpleUser::class, [
    'username' => 'maiorano84',
    'password' => 'secret',
]);
print_r($user);
/*
App\ComplexUser Object
(
    [username] => maiorano84
    [id:App\ComplexUser:private] => 1
    [email:App\ComplexUser:private] => maiorano84@gmail.com
    [password:App\ComplexUser:private] => <hashedPassword>
    [avatar:App\ComplexUser:private] => App\UserAvatar Object
        (
            [name] => maiorano84.jpg
            [id:App\UserAvatar:private] => 123
            [filePath:App\UserAvatar:private] => path/to/avatars
        )
)
*/
```