<?php

declare(strict_types=1);

namespace Zenigata\Utility\Helper;

use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Provides a unified static API for both PSR-6 and PSR-16 cache pools.
 * Operates on the cache instance passed to each method.
 */
class CacheHelper
{
    /**
     * Prevent instantiation.
     */
    private function __construct() {}

    /**
     * Retrieves a value from the cache.
     *
     * @param CacheItemPoolInterface|CacheInterface $cache The cache instance.
     * @param string                                $key   Cache key.
     *
     * @return mixed|null The cached value, or null if not found.
     */
    public static function getItem(CacheItemPoolInterface|CacheInterface $cache, string $key): mixed
    {
        if ($cache instanceof CacheInterface) {
            return $cache->get($key, null);
        }

        $item = $cache->getItem($key);
        
        return $item->isHit() ? $item->get() : null;
    }

    /**
     * Stores a value in the cache.
     *
     * @param CacheItemPoolInterface|CacheInterface $cache The cache instance.
     * @param string                                $key   Cache key.
     * @param mixed                                 $value The value to store.
     * @param int|null                              $ttl   Optional TTL in seconds.
     *
     * @return void
     */
    public static function setItem(
        CacheItemPoolInterface|CacheInterface $cache,
        string $key,
        mixed $value,
        ?int $ttl = null
    ): void {
        if ($cache instanceof CacheInterface) {
            $cache->set($key, $value, $ttl);
            return;
        }

        $item = $cache->getItem($key);
        $item->set($value);

        if ($ttl !== null) {
            $item->expiresAfter($ttl);
        }

        $cache->save($item);
    }

    /**
     * Deletes a value from the cache.
     *
     * @param CacheItemPoolInterface|CacheInterface $cache The cache instance.
     * @param string                                $key   Cache key.
     *
     * @return void
     */
    public static function deleteItem(CacheItemPoolInterface|CacheInterface $cache, string $key): void
    {
        if ($cache instanceof CacheInterface) {
            $cache->delete($key);
            return;
        }

        $cache->deleteItem($key);
    }

    /**
     * Checks if a cache key exists (unified for PSR-6/PSR-16).
     *
     * @param CacheItemPoolInterface|CacheInterface $cache The cache instance.
     * @param string                                $key   Cache key.
     *
     * @return bool
     */
    public static function hasItem(CacheItemPoolInterface|CacheInterface $cache, string $key): bool
    {
        if ($cache instanceof CacheInterface) {
            return $cache->has($key);
        }

        return $cache->getItem($key)->isHit();
    }

    /**
     * Retrieves multiple values from the cache.
     *
     * @param CacheItemPoolInterface|CacheInterface $cache The cache instance.
     * @param iterable                              $keys  List of keys to retrieve.
     *
     * @return array<string,mixed> Map of key => value (missing keys return null).
     */
    public static function getItems(CacheItemPoolInterface|CacheInterface $cache, iterable $keys): array
    {
        if ($cache instanceof CacheInterface) {
            return $cache->getMultiple($keys, null);
        }

        $items = $cache->getItems($keys);
        $result = [];

        foreach ($items as $key => $item) {
            $result[$key] = $item->isHit() ? $item->get() : null;
        }

        return $result;
    }

    /**
     * Stores multiple values in the cache.
     *
     * @param CacheItemPoolInterface|CacheInterface $cache  The cache instance.
     * @param iterable<string,mixed>                $values Key-value pairs to store.
     * @param int|null                              $ttl    Optional TTL in seconds.
     *
     * @return void
     */
    public static function setItems(
        CacheItemPoolInterface|CacheInterface $cache,
        iterable $values,
        ?int $ttl = null
    ): void {
        if ($cache instanceof CacheInterface) {
            $cache->setMultiple($values, $ttl);
            return;
        }

        foreach ($values as $key => $value) {
            $item = $cache->getItem((string) $key);
            $item->set($value);

            if ($ttl !== null) {
                $item->expiresAfter($ttl);
            }

            $cache->save($item);
        }
    }

    /**
     * Deletes multiple values from the cache.
     *
     * @param CacheItemPoolInterface|CacheInterface $cache The cache instance.
     * @param iterable<string>                      $keys  Keys to delete.
     *
     * @return void
     */
    public static function deleteItems(CacheItemPoolInterface|CacheInterface $cache, iterable $keys): void
    {
        if ($cache instanceof CacheInterface) {
            $cache->deleteMultiple($keys);
            return;
        }

        foreach ($keys as $key) {
            $cache->deleteItem((string) $key);
        }
    }

    /**
     * Clears the entire cache.
     *
     * @param CacheItemPoolInterface|CacheInterface $cache The cache instance.
     *
     * @return void
     */
    public static function clear(CacheItemPoolInterface|CacheInterface $cache): void
    {
        $cache->clear();
    }
}
