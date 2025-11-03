<?php

declare(strict_types=1);

namespace Zenigata\Utility\Config;

use IteratorAggregate;

/**
 * Defines a generic iterable collection contract.
 *
 * Ensures a consistent interface for objects that can be iterated
 * and fully converted into an array.
 */
interface IterableInterface extends IteratorAggregate
{
    /**
     * Converts the iterable content into an array.
     *
     * @return array<int,mixed> All collected items.
     */
    public function toArray(): array;
}