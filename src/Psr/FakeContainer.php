<?php

declare(strict_types=1);

namespace Zenigata\Utility\Psr;

use Exception;
use LogicException;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function array_is_list;
use function sprintf;

/**
 * Fake implementation of {@see ContainerInterface} (PSR-11).
 *
 * Provides an in-memory simulation of a PSR-11 container.
 */
class FakeContainer implements ContainerInterface
{
    /**
     * Stack of entries, stored as key-value map of IDs to services.
     * 
     * @var array<string,mixed>
     */
    public array $entries = [];

    /**
     * Creates a new fake container instance.
     *
     * @param array<string,mixed> $entries Associative array mapping service IDs to instances/values.
     * 
     * @throws LogicException If entries are not set as associative array.
     */
    public function __construct(array $entries = [])
    {
        if (!empty($entries) && array_is_list($entries)) {
            throw new LogicException(sprintf(
                "Class '%s' requires an associative array of entries.",
                static::class
            ));
        }

        $this->entries = $entries;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new class("Service '$id' not found.") extends Exception implements NotFoundExceptionInterface {};
        }

        return $this->entries[$id];
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }
}