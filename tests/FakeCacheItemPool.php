<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Fake implementation of {@see CacheItemPoolInterface} (PSR-6).

 * Provides an in-memory simulation of a PSR-6 cache backend.
 */
class FakeCacheItemPool implements CacheItemPoolInterface
{
    /** 
     * In-memory store of cache items keyed by their cache keys.
     * 
     * @var array<string,CacheItemInterface> 
     */
    public array $items = [];

    /**
     * Deferred cache items waiting to be committed.
     * 
     * @var array<string,CacheItemInterface> 
     */
    public array $deferred = [];

    /**
     * {@inheritDoc}
     */
    public function getItem(string $key): CacheItemInterface
    {
        return $this->items[$key] ?? new FakeCacheItem($key);
    }

    /**
     * {@inheritDoc}
     */
    public function getItems(array $keys = []): iterable
    {
        $items = [];
        
        foreach ($keys as $key) {
            $items[$key] = $this->getItem($key);
        }

        return $items;
    }

    /**
     * {@inheritDoc}
     */
    public function hasItem(string $key): bool
    {
        return $this->getItem($key)->isHit();
    }

    /**
     * {@inheritDoc}
     *
     * @return bool Always returns true in this fake implementation.
     */
    public function clear(): bool
    {
        $this->items = [];

        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return bool Always returns true in this fake implementation.
     */
    public function deleteItem(string $key): bool
    {
        unset($this->items[$key]);

        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return bool Always returns true in this fake implementation.
     */
    public function deleteItems(array $keys): bool
    {
        foreach ($keys as $key) {
            unset($this->items[$key]);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return bool Always returns true in this fake implementation.
     */
    public function save(CacheItemInterface $item): bool
    {
        $this->items[$item->getKey()] = $item;
        
        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return bool Always returns true in this fake implementation.
     */
    public function saveDeferred(CacheItemInterface $item): bool
    {
        $this->deferred[$item->getKey()] = $item;
        
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool Always returns true in this fake implementation.
     */
    public function commit(): bool
    {
        foreach ($this->deferred as $item) {
            $this->save($item);
        }

        $this->deferred = [];
        return true;
    }
}