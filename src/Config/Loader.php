<?php

declare(strict_types=1);

namespace Zenigata\Utility\Config;

use Countable;
use Generator;
use IteratorAggregate;
use RuntimeException;

use function count;
use function is_file;
use function is_readable;
use function iterator_to_array;

/**
 * Memory-efficient configuration loader.
 *
 * Loads configuration files lazily using generators to minimize memory usage,
 * providing an array-like development experience.
 */
final class Loader
{
    /**
     * Creates a new loader instance.
     *
     * @param array<string,string|string[]> $paths Array of file paths, optionally grouped by labels.
     */
    public function __construct(
        private array $paths
    ) {}

    /**
     * Load configuration files lazily.
     *
     * @param string|null $label Optional label to filter files.
     * 
     * @return iterable<mixed> Lazy, array-like iterable of file contents.
     * @throws RuntimeException If the label is not found or if a file is invalid/unreadable.
     */
    public function load(?string $label = null): iterable
    {
        $paths = $this->resolvePaths($label);

        return new class($paths) implements Countable, IteratorAggregate {
            public function __construct(
                private array $paths
            ) {}

            public function count(): int
            {
                return count($this->paths);
            }

            public function getIterator(): Generator
            {
                foreach ($this->paths as $path) {
                    if (!is_file($path) || !is_readable($path)) {
                        throw new RuntimeException("Invalid or unreadable file: $path");
                    }

                    yield require $path;
                }
            }

            public function toArray(): array
            {
                return iterator_to_array($this->getIterator(), false);
            }
        };
    }

    /**
     * Returns an array of file paths to load, filtering for label if provided.
     * 
     * @return string[] Array of file paths.
     * @throws RuntimeException If the label does not exist.
     */
    private function resolvePaths(?string $label = null): array
    {
        if ($label !== null) {
            if (!isset($this->paths[$label])) {
                throw new RuntimeException("No entries found for label: $label");
            }

            return (array) $this->paths[$label];
        }

        $paths = [];

        foreach ($this->paths as $entry) {
            foreach ((array) $entry as $path) {
                $paths[] = $path;
            }
        }

        return $paths;
    }
}
