<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenigata\Utility\CacheHelper;
use Zenigata\Utility\Test\FakeCacheItemPool;
use Zenigata\Utility\Test\FakeSimpleCache;

/**
 * Unit test for {@see CacheHelper} utility.
 *
 * Covered cases:
 * 
 * - Read, write, and delete single cache items.
 * - Null returned if key does not exist.
 * - TTL support when setting cache items.
 * - Checking existence with {@see CacheHelper::hasItem()}.
 * - Bulk operations: set, get, delete multiple items.
 * - Clear the entire cache.
 * - Support for both PSR-6 and PSR-16 backends.
 */
#[CoversClass(CacheHelper::class)]
final class CacheHelperTest extends TestCase
{
    // === PSR-6 ===

    public function testPsr6CanWriteAndReadCacheItem(): void
    {
        $cache = new FakeCacheItemPool();

        CacheHelper::setItem($cache, 'foo', 'bar');
        $value = CacheHelper::getItem($cache, 'foo');

        $this->assertSame('bar', $value);
    }

    public function testPsr6ReturnNullIfItemNotHit(): void
    {
        $cache = new FakeCacheItemPool();
        
        $this->assertNull(CacheHelper::getItem($cache, 'missing'));
    }

    public function testPsr6CanDeleteCacheItem(): void
    {
        $cache = new FakeCacheItemPool();
        
        CacheHelper::setItem($cache, 'foo', 'bar');

        $this->assertSame('bar', CacheHelper::getItem($cache, 'foo'));

        CacheHelper::deleteItem($cache, 'foo');

        $this->assertNull(CacheHelper::getItem($cache, 'foo'));
    }

    public function testPsr6SetCacheItemWithTtl(): void
    {
        $cache = new FakeCacheItemPool();
        
        CacheHelper::setItem($cache, 'foo', 'bar', ttl: 3600);

        $this->assertSame('bar', CacheHelper::getItem($cache, 'foo'));
    }

    public function testPsr6BulkOperations(): void
    {
        $cache = new FakeCacheItemPool();

        CacheHelper::setItems($cache, ['a' => 1, 'b' => 2]);
        $items = CacheHelper::getItems($cache, ['a', 'b', 'c']);

        $this->assertSame(['a' => 1, 'b' => 2, 'c' => null], $items);

        CacheHelper::deleteItems($cache, ['a', 'b']);

        $this->assertNull(CacheHelper::getItem($cache, 'a'));
        $this->assertNull(CacheHelper::getItem($cache, 'b'));
    }

    public function testPsr6ClearCache(): void
    {
        $cache = new FakeCacheItemPool();

        CacheHelper::setItem($cache, 'foo', 'bar');

        $this->assertTrue(CacheHelper::hasItem($cache, 'foo'));

        CacheHelper::clear($cache);

        $this->assertFalse(CacheHelper::hasItem($cache, 'foo'));
    }

    // === PSR-16 ===

    public function testPsr16CanWriteAndReadCacheItem(): void
    {
        $cache = new FakeSimpleCache();

        CacheHelper::setItem($cache, 'foo', 'bar');
        
        $this->assertSame('bar', CacheHelper::getItem($cache, 'foo'));
    }

    public function testPsr16ReturnNullIfItemNotFound(): void
    {
        $cache = new FakeSimpleCache();

        $this->assertNull(CacheHelper::getItem($cache, 'missing'));
    }

    public function testPsr16DeleteCacheItem(): void
    {
        $cache = new FakeSimpleCache();

        CacheHelper::setItem($cache, 'foo', 'bar');

        $this->assertSame('bar', CacheHelper::getItem($cache, 'foo'));

        CacheHelper::deleteItem($cache, 'foo');

        $this->assertNull(CacheHelper::getItem($cache, 'foo'));
    }

    public function testPsr16BulkOperations(): void
    {
        $cache = new FakeSimpleCache();

        CacheHelper::setItems($cache, ['x' => 10, 'y' => 20]);
        $items = CacheHelper::getItems($cache, ['x', 'y', 'z']);

        $this->assertSame(['x' => 10, 'y' => 20, 'z' => null], $items);

        CacheHelper::deleteItems($cache, ['x', 'y']);

        $this->assertNull(CacheHelper::getItem($cache, 'x'));
        $this->assertNull(CacheHelper::getItem($cache, 'y'));
    }

    public function testPsr16ClearCache(): void
    {
        $cache = new FakeSimpleCache();

        CacheHelper::setItem($cache, 'foo', 'bar');

        $this->assertTrue(CacheHelper::hasItem($cache, 'foo'));

        CacheHelper::clear($cache);

        $this->assertFalse(CacheHelper::hasItem($cache, 'foo'));
    }
}