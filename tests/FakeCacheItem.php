<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Psr\Cache\CacheItemInterface;

/**
 * Fake implementation of {@see CacheItemInterface} (PSR-6).
 */
class FakeCacheItem implements CacheItemInterface
{
    /**
     * Creates a new fake cache item instance.
     *
     * @param string                 $key        The key for this cache item.
     * @param mixed                  $value      The value stored in the cache item.
     * @param bool                   $hit        Whether the cache item is considered a cache hit.
     * @param DateTimeInterface|null $expiration Optional expiration time of the cache item.
     */
    public function __construct(
        private string $key,
        private mixed $value = null,
        private bool $hit = false,
        private ?DateTimeInterface $expiration = null,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): mixed
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function isHit(): bool
    {
       if (!$this->hit) {
            return false;
        }

        if ($this->expiration === null) {
            return true;
        }

        return $this->expiration > new DateTimeImmutable();
    }

    /**
     * {@inheritDoc}
     */
    public function set(mixed $value): static
    {
        $this->value = $value;
        $this->hit = true;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function expiresAt(?DateTimeInterface $expiration): static
    {
        $this->expiration = $expiration;
        
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function expiresAfter(int|DateInterval|null $time): static
    {
        if ($time === null) {
            $this->expiration = null;
        } else {
            $this->expiration = $time instanceof DateInterval
                ? (new DateTimeImmutable())->add($time)
                : (new DateTimeImmutable())->modify("+$time seconds");
        }

        return $this;
    }
}