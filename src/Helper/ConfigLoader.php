<?php

declare(strict_types=1);

namespace Zenigata\Utility\Helper;

use RuntimeException;

use function is_file;
use function is_readable;
use function sprintf;

/**
 * Memory-efficient configuration loader.
 * Loads configuration files lazily using generators to minimize memory usage.
 * 
 * Example:
 * 
 * ```php
 * use Zenigata\Utility\Helper\ConfigLoader;
 * 
 * // config/app.php
 * return ['name' => 'MyApp'];
 * 
 * // config/db.php
 * return ['driver' => 'mysql'];
 * 
 * $configs = ConfigLoader::load([
 *     __DIR__ . '/config/app.php',
 *     __DIR__ . '/config/db.php',
 * ]);
 * 
 * foreach ($configs as $config) {
 *     var_dump($config);
 * }
 * 
 * // Or convert to array
 * $configs = iterator_to_array(ConfigLoader::load([
 *     __DIR__ . '/config/app.php',
 *     __DIR__ . '/config/db.php',
 * ]));
 * 
 * var_dump($configs);
 * ```
 */
final class ConfigLoader
{
    /**
     * Prevent instantiation.
     */
    private function __construct() {}

    /**
     * Load configuration files lazily.
     *
     * @param iterable<string> $paths Iterable list of configuration file paths.
     *
     * @return iterable<mixed> Values returned by each configuration file.
     * @throws RuntimeException If a file is invalid or not readable.
     */
    public static function load(iterable $paths): iterable
    {
        foreach ($paths as $path) {
            if (!is_file($path) || !is_readable($path)) {
                throw new RuntimeException(sprintf(
                    "Invalid or unreadable file: '%s'.",
                    $path
                ));
            }

            yield require $path;
        }
    }
}
