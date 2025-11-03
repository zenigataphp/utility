<?php

declare(strict_types=1);

namespace Zenigata\Utility\Config;

use IteratorAggregate;

/**
 * Represents an iterable, array-like collection.
 *
 * Provides countability and a method to convert all items into an array.
 */
interface IterableInterface extends IteratorAggregate
{
    /**
     * Converts the collection into a plain array.
     *
     * @return array<int,mixed> Collected items.
     */
    public function toArray(): array;
}