<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test;

use DateInterval;
use DateTimeImmutable;
use InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;

use function array_key_exists;
use function is_int;

/**
 * Fake implementation of {@see CacheInterface} (PSR-16).
 *
 * Provides an in-memory simulation of a PSR-16 cache backend.
 */
class FakeSimpleCache implements CacheInterface
{
    /**
     * Internal cache storage.
     * 
     * Each entry is stored as an array of two elements:
     * 
     *  - mixed              The cached value.
     *  - ?DateTimeImmutable The expiration timestamp, or null for no expiration.
     *
     * @var array<string,array{mixed, ?DateTimeImmutable}>
     */
    public array $items = [];

    /**
     * {@inheritDoc}
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (!array_key_exists($key, $this->items)) {
            return $default;
        }

        [$value, $expiration] = $this->items[$key];

        if ($expiration !== null && $expiration < new DateTimeImmutable()) {
            unset($this->items[$key]);
            return $default;
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     * 
     * @return bool Always returns true in this fake implementation.
     */
    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool
    {
        $expiration = $this->normalizeTtl($ttl);
        $this->items[$key] = [$value, $expiration];

        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return bool Always returns true in this fake implementation.
     */
    public function delete(string $key): bool
    {
        unset($this->items[$key]);

        return true;
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
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     * 
     * @return bool Always returns true in this fake implementation.
     */
    public function setMultiple(iterable $values, null|int|DateInterval $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return bool Always returns true in this fake implementation.
     */
    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $key): bool
    {
        if (!array_key_exists($key, $this->items)) {
            return false;
        }

        [, $expiration] = $this->items[$key];

        if ($expiration !== null && $expiration < new DateTimeImmutable()) {
            unset($this->items[$key]);
            return false;
        }

        return true;
    }

    /**
     * Converts a TTL value to an absolute expiration date.
     * 
     * @return DateTimeImmutable|null The expiration timestamp, or null for no expiration.
     * @throws InvalidArgumentException If the TTL value is invalid.
     */
    private function normalizeTtl(DateInterval|int|null $ttl): ?DateTimeImmutable
    {
        if ($ttl === null) {
            return null;
        }

        $now = new DateTimeImmutable();

        if ($ttl instanceof DateInterval) {
            return $now->add($ttl);
        }

        if (is_int($ttl)) {
            return $now->modify("+{$ttl} seconds");
        }

        throw new InvalidArgumentException('Invalid TTL');
    }
}
