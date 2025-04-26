<?php

/*
 * This file is part of the PDF Version Converter.
 *
 * (c) Thiago Rodrigues <xthiago@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Xthiago\PDFVersionConverter\Converter;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Thiago Rodrigues <xthiago@gmail.com>
 */
class GhostscriptConverterTest extends TestCase
{
    protected $tmp;

    protected function setUp(): void
    {
        $this->tmp = __DIR__.'/../files/stage/';

        if (!file_exists($this->tmp))
            mkdir($this->tmp);
    }

    /**
     * @param string $file
     * @param $newVersion
     *
     * @dataProvider filesProvider
     */
    public function testMustConvertPDFVersionWithSuccess(string $file, string $newVersion): void
    {
        $fs = $this->createMock(Filesystem::class);

        $fs->expects($this->once())
            ->method('exists')
            ->with($this->isType('string'))
            ->willReturn(true);

        $fs->expects($this->once())
            ->method('copy')
            ->with(
                $this->isType('string'),
                $this->equalTo($file),
                $this->equalTo(true)
            );

        $command = $this->createMock(\Xthiago\PDFVersionConverter\Converter\GhostscriptConverterCommand::class);

        $command->expects($this->once())
            ->method('run')
            ->with(
                $this->equalTo($file),
                $this->isType('string'),
                $this->equalTo($newVersion)
            )
            ->willReturn(null);

        $converter = new \Xthiago\PDFVersionConverter\Converter\GhostscriptConverter(
            $command,
            $fs,
            $this->tmp
        );
        $converter->convert($file, $newVersion);
    }

    /**
     * @return array
     */
    public static function filesProvider()
    {
        return array(
            // file, new version
            array(__DIR__ . '/../files/stage/v1.1.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.2.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.3.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.4.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.5.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.6.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.7 filename with "Sp3ci4l"; <\'Ch4r5\'> !£$%&()=?^[]{}è@#§.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.7.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v2.0.pdf', '1.4'),
        );
    }
}