<?php

declare(strict_types=1);

namespace MageSuite\ThemeHelpers\Test\Unit\Helper;

class ImageTypeTest extends \PHPUnit\Framework\TestCase
{
    protected ?\MageSuite\ThemeHelpers\Helper\ImageType $imageType;

    public function setUp(): void
    {
        $this->imageType = new \MageSuite\ThemeHelpers\Helper\ImageType(
            $this->createStub(\Magento\Framework\App\Helper\Context::class)
        );
    }

    /**
     * @dataProvider getMimeTypeUrls
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('getMimeTypeUrls')]
    public function testItResolvesMimeTypes(string $path, string $expectedResult): void
    {
        $this->assertEquals($expectedResult, $this->imageType->getMimeType($path));
    }

    public static function getMimeTypeUrls(): array
    {
        return [
            ['/var/images/image.jpg', 'image/jpeg'],
            ['https://www.example.com/image.jpg', 'image/jpeg'],
            ['https://www.example.com/image.jpeg', 'image/jpeg'],
            ['https://www.example.com/image.png', 'image/png'],
            ['https://www.example.com/image.PNG', 'image/png'],
            ['https://www.example.com/image.gif', 'image/gif'],
        ];
    }
}
