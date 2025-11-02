<?php

declare(strict_types=1);

namespace Zenigata\Utility\Test\Unit\Config;

use RuntimeException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Zenigata\Utility\Config\Loader;

use function sprintf;
use function var_export;

/**
 * Unit test for {@see Loader} utility.
 *
 * Covered cases:
 * 
 * - Load configuration from single or multiple files.
 * - Support for grouped configurations using labels.
 * - Provide array-like interface (Countable, toArray).
 * - Throw when label does not exist.
 * - Throw when file does not exist or is not readable.
 */
#[CoversClass(Loader::class)]
final class LoaderTest extends TestCase
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
            'db.php'     => $this->php(['db' => 'mysql']),
            'cache.php'  => $this->php(['cache' => 'redis']),
            'app.php'    => $this->php(['app' => 'web']),
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
        $loader = new Loader([
            $this->files['db']
        ]);
        
        $result = $loader->load();
        $values = $result->toArray();

        $this->assertCount(1, $result);
        $this->assertCount(1, $values);
        $this->assertSame(['db' => 'mysql'], $values[0]);
    }

    public function testLoadFilesWithLabels(): void
    {
        $loader = new Loader([
            'config' => [
                $this->files['db'],
                $this->files['cache']
            ]
        ]);

        $result = $loader->load('config');
        $values = $result->toArray();

        $this->assertCount(2, $result);
        $this->assertCount(2, $values);
        $this->assertSame(['db' => 'mysql'], $values[0]);
        $this->assertSame(['cache' => 'redis'], $values[1]);
    }

    public function testThrowIfLabelDoesNotExist(): void
    {
        $loader = new Loader([
            'config' => [$this->files['db']]
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No entries found for label: missing');

        $loader->load('missing');
    }

    public function testThrowIfFileDoesNotExist(): void
    {
        $loader = new Loader([
            vfsStream::url('root/missing.php')
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid or unreadable file');

        $loader->load()->toArray();
    }

    public function testThrowIfFileNotReadable(): void
    {
        $loader = new Loader([
            $this->files['secret']
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid or unreadable file');

        $loader->load()->toArray();
    }

    /**
     * Generates valid PHP file content that returns the specified value.
     */
    private function php(mixed $value): string
    {
        return sprintf('<?php return %s;', var_export($value, return: true));
    }
}
