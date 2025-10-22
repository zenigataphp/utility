# Zenigata Utility

A lightweight collection of PHP utility classes to simplify common development tasks.
Currently includes helpers for **cache interaction**, **container resolution**, and **stub file generation**.

## Features

- Unified API for [PSR-6](https://www.php-fig.org/psr/psr-6/#interfaces) and [PSR-16](https://www.php-fig.org/psr/psr-16/#interfaces) interfaces.
- Simple service resolution with optional **type validation** from a [PSR-11](https://www.php-fig.org/psr/psr-11/#3-interfaces) container.
- Stub file generation with **token replacement** and safe directory handling.

## Requirements

- PHP >= 8.2
- [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)

## Installation

```bash
composer require zenigata/utility
```

## Usage

### `CacheHelper`

Utility for interacting with both **PSR-6** and **PSR-16** caches with a unified API.

It hides the differences between the two standards, so you can use the **same methods** regardless of the underlying cache implementation.

```php
use Psr\Cache\CacheItemPoolInterface; // PSR-6
use Psr\SimpleCache\CacheInterface;   // PSR-16
use Zenigata\Utility\CacheHelper;

// $cache implements CacheItemPoolInterface or CacheInterface

// With PSR-6
$item = $cache->getItem('foo');
$value = $item->isHit() ? $item->get() : null;

// With PSR-16
$value = $cache->get('foo', null);

// With CacheHelper: same code works for both
$value = CacheHelper::getItem($cache, 'foo');

// With PSR-6
$item = $cache->getItem('foo');
$item->set('bar')->expiresAfter(3600);
$cache->save($item);

// With PSR-16
$cache->set('foo', 'bar', 3600);

// With CacheHelper
CacheHelper::setItem($cache, 'foo', 'bar', 3600);
```

Supports single and multiple operations:

- `getItem`, `setItem`, `deleteItem`, `hasItem`
- `getItems`, `setItems`, `deleteItems`, `clear`

### `ReflectionHelper`

A **lightweight utility** for instantiating classes with empty constructors using PHP reflection.

Useful when no **dependency injection container** is available — for example in low-level bootstrap logic or test utilities.

```php
use Zenigata\Utility\ReflectionHelper;

final class MyService
{
    public function __construct() {}
}

$instance = ReflectionHelper::instantiate(MyService::class);

var_dump($instance instanceof MyService); // true
```

Provides clear, **developer-friendly error messages** for all failure cases:

- `"Cannot instantiate 'Foo': class not found."`
- `"Cannot instantiate 'Foo': class is not instantiable. Ensure the target is a concrete class."`
- `"Cannot instantiate 'Foo': constructor defines 2 required parameter(s)."`

### `StubRenderer`

Utility for generating files from **stub templates** with placeholder replacement and automatic directory creation.

```php
use Zenigata\Utility\StubRenderer;

/*
Stub file: stubs/Class.stub

<?php

namespace {{namespace}};

class {{class}}
{
    public function hello(): string
    {
        return "Hello World!";
    }
}
*/

StubRenderer::render(
    stub:         __DIR__ . '/stubs/Class.stub',
    destination:  __DIR__ . '/src/MyClass.php',
    placeholders: [
        '{{namespace}}' => 'Example',
        '{{class}}'     => 'MyClass',
    ]
);

/*
Resulting file: src/MyClass.php

<?php

namespace Example;

class MyClass
{
    public function hello(): string
    {
        return "Hello from MyClass!";
    }
}
*/
```

- Replaces placeholders (e.g. `{{namespace}}`, `{{class}}`) with given values.
- Ensures the destination directory exists.
- Throws a `RuntimeException` if the stub cannot be read or the file cannot be written.

## Contributing

Pull requests are welcome! For major changes, please open an issue first to discuss what you would like to change.

Keep the implementation minimal, focused, and well-documented, making sure to update tests accordingly.

See [CONTRIBUTING](./CONTRIBUTING.md) for more information.

## License

This library is licensed under the MIT license. See [LICENSE](./LICENSE) for more information.

