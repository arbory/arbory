<?php

declare(strict_types=1);

namespace Tests\Admin\Form\Fields\Renderer;

use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;
use Arbory\Base\Files\ArboryFile;
use Arbory\Base\Admin\Form\Fields\Helpers\FileSize;

/**
 * Class FileSizeTest.
 */
final class FileSizeTest extends TestCase
{
    /**
     * @var Mock|ArboryFile
     */
    private $file;

    /**
     * @var FileSize
     */
    private $fileSize;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->file = Mockery::mock(ArboryFile::class);
        $this->fileSize = new FileSize($this->file);
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     * @return void
     */
    public function itShouldHaveSizeInBytes()
    {
        $expectedFileSize = 1234560;

        /* @noinspection PhpMethodParametersCountMismatchInspection */
        $this->file->shouldReceive('getSize')->andReturn($expectedFileSize);
        $this->assertEquals($this->fileSize->getSizeInBytes(), $expectedFileSize);
    }

    /**
     * @test
     * @return void
     */
    public function itShouldConvertSizeInBytesToReadableValue()
    {
        $this->assertSizeGetsConvertedToReadableValue(123, '0.12 KB');
        $this->assertSizeGetsConvertedToReadableValue(3189, '3.19 KB');
        $this->assertSizeGetsConvertedToReadableValue(40960, '40.96 KB');
        $this->assertSizeGetsConvertedToReadableValue(590123, '0.59 MB');
        $this->assertSizeGetsConvertedToReadableValue(42590123, '42.59 MB');
        $this->assertSizeGetsConvertedToReadableValue(1042590123, '1042.59 MB');
    }

    /**
     * @param int $sizeInBytes
     * @param string $expectedValue
     * @return void
     */
    private function assertSizeGetsConvertedToReadableValue(int $sizeInBytes, string $expectedValue)
    {
        /* @noinspection PhpMethodParametersCountMismatchInspection */
        $this->file->shouldReceive('getSize')->once()->andReturn($sizeInBytes);
        $this->assertEquals($expectedValue, $this->fileSize->getReadableSize());
    }
}
