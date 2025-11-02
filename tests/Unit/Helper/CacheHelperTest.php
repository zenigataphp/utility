<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Zenigata\Utility\Helper\CacheHelper;

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
    private CacheItemPoolInterface $psr6;

    private CacheInterface $psr16;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->psr6 = new ArrayAdapter();
        $this->psr16 = new Psr16Cache(new ArrayAdapter());
    }

    // === PSR-6 ===
    
    public function testPsr6CanWriteAndReadCacheItem(): void
    {
        CacheHelper::setItem($this->psr6, 'foo', 'bar');
        $value = CacheHelper::getItem($this->psr6, 'foo');

        $this->assertSame('bar', $value);
    }

    public function testPsr6ReturnNullIfItemNotHit(): void
    {
       
        $this->assertNull(CacheHelper::getItem($this->psr6, 'missing'));
    }

    public function testPsr6CanDeleteCacheItem(): void
    {
       
        CacheHelper::setItem($this->psr6, 'foo', 'bar');

        $this->assertSame('bar', CacheHelper::getItem($this->psr6, 'foo'));

        CacheHelper::deleteItem($this->psr6, 'foo');

        $this->assertNull(CacheHelper::getItem($this->psr6, 'foo'));
    }

    public function testPsr6SetCacheItemWithTtl(): void
    {
       
        CacheHelper::setItem($this->psr6, 'foo', 'bar', ttl: 3600);

        $this->assertSame('bar', CacheHelper::getItem($this->psr6, 'foo'));
    }

    public function testPsr6BulkOperations(): void
    {
        CacheHelper::setItems($this->psr6, ['a' => 1, 'b' => 2]);
        $items = CacheHelper::getItems($this->psr6, ['a', 'b', 'c']);

        $this->assertSame(['a' => 1, 'b' => 2, 'c' => null], $items);

        CacheHelper::deleteItems($this->psr6, ['a', 'b']);

        $this->assertNull(CacheHelper::getItem($this->psr6, 'a'));
        $this->assertNull(CacheHelper::getItem($this->psr6, 'b'));
    }

    public function testPsr6ClearCache(): void
    {
        CacheHelper::setItem($this->psr6, 'foo', 'bar');

        $this->assertTrue(CacheHelper::hasItem($this->psr6, 'foo'));

        CacheHelper::clear($this->psr6);

        $this->assertFalse(CacheHelper::hasItem($this->psr6, 'foo'));
    }

    // === PSR-16 ===

    public function testPsr16CanWriteAndReadCacheItem(): void
    {
        CacheHelper::setItem($this->psr16, 'foo', 'bar');
        
        $this->assertSame('bar', CacheHelper::getItem($this->psr16, 'foo'));
    }

    public function testPsr16ReturnNullIfItemNotFound(): void
    {
        $this->assertNull(CacheHelper::getItem($this->psr16, 'missing'));
    }

    public function testPsr16DeleteCacheItem(): void
    {
        CacheHelper::setItem($this->psr16, 'foo', 'bar');

        $this->assertSame('bar', CacheHelper::getItem($this->psr16, 'foo'));

        CacheHelper::deleteItem($this->psr16, 'foo');

        $this->assertNull(CacheHelper::getItem($this->psr16, 'foo'));
    }

    public function testPsr16BulkOperations(): void
    {
        CacheHelper::setItems($this->psr16, ['x' => 10, 'y' => 20]);
        $items = CacheHelper::getItems($this->psr16, ['x', 'y', 'z']);

        $this->assertSame(['x' => 10, 'y' => 20, 'z' => null], $items);

        CacheHelper::deleteItems($this->psr16, ['x', 'y']);

        $this->assertNull(CacheHelper::getItem($this->psr16, 'x'));
        $this->assertNull(CacheHelper::getItem($this->psr16, 'y'));
    }

    public function testPsr16ClearCache(): void
    {
        CacheHelper::setItem($this->psr16, 'foo', 'bar');

        $this->assertTrue(CacheHelper::hasItem($this->psr16, 'foo'));

        CacheHelper::clear($this->psr16);

        $this->assertFalse(CacheHelper::hasItem($this->psr16, 'foo'));
    }
}
