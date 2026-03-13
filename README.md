# Zenigata Utility

> ⚠️ This project is in an early development stage. Feedback and contributions are welcome!

Lightweight collection of PHP utility classes to simplify common development tasks.

The library provides small, focused helpers and traits that reduce boilerplate when working with **PSR standards**, configuration files, reflection, and more.
Each component is designed to be **framework-agnostic**, minimal, and easy to integrate into any PHP project.

## Requirements

- PHP >= 8.2
- [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)

## Installation

```bash
composer require zenigata/utility
```

## Overview

### Awareness

Traits that provide **awareness for common dependencies**, allowing classes to receive services such as containers, factories, or debug state without requiring dependency injection.

- [`ContainerAwareTrait`](./src/Awareness/ContainerAwareTrait.php) provides access to a **PSR-11 container** and helper methods for retrieving services.
- [`DebugAwareTrait`](./src/Awareness/DebugAwareTrait.php) adds a configurable **debug flag** to enable or disable development features.
- [`ResponseFactoryAwareTrait`](./src/Awareness/ResponseFactoryAwareTrait.php) provides access to a **PSR-17 response factory** for creating HTTP responses.
- [`StreamFactoryAwareTrait`](./src/Awareness/StreamFactoryAwareTrait.php) provides access to a **PSR-17 stream factory** for creating HTTP streams.

### Helper

Collection of small utilities that simplify common tasks such as configuration loading, caching interoperability, file generation, and more.

- [`CacheHelper`](./src/Helper/CacheHelper.php) for interacting with both **PSR-6** and **PSR-16** caches with a unified API. It hides the differences between the two standards, so you can use the **same methods** regardless of the underlying cache implementation.
- [`ConfigLoader`](./src/Helper/ConfigLoader.php) for lazily loading PHP configuration files using **generators**, ensuring minimal memory usage when working with multiple config files.
- [`ReflectionResolver`](./src/Helper/ReflectionResolver.php) for instantiating classes with empty constructors using **PHP reflection**, useful when no **dependency injection container** is available.
- [`StubRenderer`](./src/Helper/StubRenderer.php) for generating files from **stub templates** with placeholder replacement and automatic directory creation.

### Testing

Simple **test doubles** designed to simplify unit testing when full implementations are unnecessary.

- [`FakeContainer`](./src/Testing/FakeContainer.php): in-memory implementation of **PSR-11** `ContainerInterface`, useful for testing container-aware classes and manually registering dependencies.
- [`FakeLogger`](./src/Testing/FakeLogger.php): lightweight **PSR-3 logger** that records log messages in memory, allowing tests to inspect and assert logged output.
- [`FakeMiddleware`](./src/Testing/FakeMiddleware.php): configurable **PSR-15 middleware** that can optionally return a predefined response or execute a callback during processing, making it easy to test middleware pipelines.
- [`FakeRequestHandler`](./src/Testing/FakeRequestHandler.php): simple **PSR-15 request handler** returning a predefined response, with optional callback execution to observe request handling during tests.

## Contributing

Pull requests are welcome! For major changes, please open an issue first to discuss what you would like to change.

Keep the implementation minimal, focused, and well-documented, making sure to update tests accordingly.

See [CONTRIBUTING](./CONTRIBUTING.md) for more information.

## License

This library is licensed under the MIT license. See [LICENSE](./LICENSE) for more information.

