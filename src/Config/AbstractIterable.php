<?php

declare(strict_types=1);

namespace Zenigata\Utility\Config;

use Traversable;

use function iterator_to_array;

/**
 * Base class for {@see Zenigata\Utility\Config\IterableInterface} implementations.
 *
 * Implements common behavior for converting iterable data into an array,
 * leaving iteration logic to concrete subclasses.
 */
abstract class AbstractIterable implements IterableInterface
{
    /**
     * @inheritDoc
     * 
     * Returns an iterator over the collection items.
     *
     * Concrete classes must implement this method to define
     * how iteration occurs.
     */
    abstract public function getIterator(): Traversable;

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return iterator_to_array($this->getIterator(), false);
    }
}