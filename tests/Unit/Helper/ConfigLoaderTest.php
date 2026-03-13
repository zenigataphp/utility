<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit\Helper;

use Generator;
use RuntimeException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenigata\Utility\Helper\ConfigLoader;

use function sprintf;
use function var_export;

/**
 * Unit test for {@see Zenigata\Utility\Helper\ConfigLoader} utility.
 *
 * Covered cases:
 * 
 * - Load configuration from single or multiple files.
 * - Provide lazy loading of configuration data.
 * - Throw when label does not exist.
 * - Throw when file does not exist or is not readable.
 */
#[CoversClass(ConfigLoader::class)]
final class ConfigLoaderTest extends TestCase
{
    private vfsStreamDirectory $root;

    /**
     * Map of file keys to virtual paths.
     * 
     * @var array<string,string>
     */
    private array $files;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->root = vfsStream::setup('config', null, [
            'db.php'     => $this->php(['db'    => 'mysql']),
            'cache.php'  => $this->php(['cache' => 'redis']),
            'app.php'    => $this->php(['app'   => 'web']),
            'unreadable' => [
                'secret.php' => $this->php(['secret' => 'hidden']),
            ],
        ]);

        // Permissions to simulate an unreadable file
        $this->root->getChild('unreadable/secret.php')->chmod(0000);

        $this->files = [
            'db'     => vfsStream::url('config/db.php'),
            'cache'  => vfsStream::url('config/cache.php'),
            'app'    => vfsStream::url('config/app.php'),
            'secret' => vfsStream::url('config/unreadable/secret.php'),
        ];
    }

    public function testLoadSingleFile(): void
    {
        $iterable = ConfigLoader::load([
            $this->files['db']
        ]);

        $values = $this->resolveGenerator($iterable);

        $this->assertCount(1, $values);
        $this->assertSame(['db' => 'mysql'], $values[0]);
    }

    public function testLoadMultipleFiles(): void
    {
        $iterable = ConfigLoader::load([
            $this->files['db'],
            $this->files['cache']
        ]);

        $values = $this->resolveGenerator($iterable);

        $this->assertCount(2, $values);
        $this->assertSame(['db' => 'mysql'], $values[0]);
        $this->assertSame(['cache' => 'redis'], $values[1]);
    }

    public function testThrowIfFileDoesNotExist(): void
    {
        $iterable = ConfigLoader::load([
            vfsStream::url('root/missing.php')
        ]);
        
        // Verify lazyness
        $this->assertInstanceOf(Generator::class, $iterable);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid or unreadable file');

        $this->resolveGenerator($iterable);
    }

    public function testThrowIfFileNotReadable(): void
    {
        $iterable = ConfigLoader::load([
            $this->files['secret']
        ]);

        // Verify lazyness
        $this->assertInstanceOf(Generator::class, $iterable);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid or unreadable file');
        
        $this->resolveGenerator($iterable);
    }

    /**
     * Generates valid PHP file content that returns the specified value.
     */
    private function php(mixed $value): string
    {
        return sprintf('<?php return %s;', var_export($value, return: true));
    }

    /**
     * Returns the iterable as an array. 
     */
    private function resolveGenerator(Generator $generator): array
    {
        return iterator_to_array($generator, true);
    }
}
