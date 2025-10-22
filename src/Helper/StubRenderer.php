<?php

declare(strict_types=1);

namespace Zenigata\Utility\Helper;

use RuntimeException;

use function array_values;
use function dirname;
use function file_get_contents;
use function file_put_contents;
use function is_dir;
use function is_file;
use function is_readable;
use function mkdir;
use function str_replace;

/**
 * Generates files from stub templates, replacing placeholders with provided values.
 * It also ensures that the destination directory exists before writing.
 */
class StubRenderer
{
    /**
     * Prevent instantiation.
     */
    private function __construct() {}

    /**
     * Generates a file from a stub template.
     *
     * @param string $stub         The path to the stub template file.
     * @param string $destination  The path where the generated file will be saved.
     * @param array  $placeholders An associative array of placeholders and their replacements.
     * 
     * @return void
     * @throws RuntimeException If the stub file is missing, unreadable, or the output file cannot be written.
     */
    public static function render(string $stub, string $destination, array $placeholders = []): void
    {
        if (!is_file($stub) || !is_readable($stub)) {
            throw new RuntimeException("Stub file '$stub' does not exist or is not a readable.");
        }

        $content = file_get_contents($stub);
        
        if ($content === false) {
            throw new RuntimeException("Failed to read stub file: {$stub}");
        }

        $content = self::replacePlaceholders($content, $placeholders);

        self::ensureDirectoryExists(dirname($destination));

        if (@file_put_contents($destination, $content) === false) {
            throw new RuntimeException("Failed to write to file: {$destination}");
        }
    }

    /**
     * Replaces placeholders in the given content.
     */
    private static function replacePlaceholders(string $content, array $replacements): string
    {
        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    /**
     * Ensures that a directory exists, creating it if necessary.
     * 
     * @throws RuntimeException If the directory cannot be created.
     */
    private static function ensureDirectoryExists(string $path): void
    {
        if (self::directoryExists($path)) return;

        if (!@mkdir($path, 0777, true) && !self::directoryExists($path)) {
            throw new RuntimeException("Unable to create directory: {$path}");
        }
    }

    /**
     * Checks whether a directory exists and is readable.
     */
    private static function directoryExists(string $path): bool
    {
        return is_dir($path) && is_readable($path);
    }
}