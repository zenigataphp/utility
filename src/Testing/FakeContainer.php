<?php

declare(strict_types=1);

namespace Zenigata\Utility\Testing;

use Exception;
use LogicException;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function array_is_list;
use function sprintf;

/**
 * Fake implementation of {@see Psr\Container\ContainerInterface} (PSR-11).
 *
 * Provides an in-memory simulation of a PSR-11 container.
 */
class FakeContainer implements ContainerInterface
{
    /**
     * Creates a new fake container instance.
     *
     * @param array<string,mixed> $entries Associative array mapping identifiers to services.
     * 
     * @throws LogicException If entries are not set as associative array.
     */
    public function __construct(
        private array $entries = []
    ) {
        if ($this->entries !== [] && array_is_list($this->entries)) {
            throw new LogicException(sprintf(
                "Class '%s' requires an associative array of entries.",
                static::class
            ));
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new class("Service '$id' not found.") extends Exception implements NotFoundExceptionInterface {};
        }

        return $this->entries[$id];
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    /**
     * Registers a service with an identifier.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function set(string $id, mixed $value): void
    {
        $this->entries[$id] = $value;
    }

    /**
     * Returns the registered services.
     * 
     * @var array<string,mixed>
     */
    public function all(): array
    {
        return $this->entries;
    }
}