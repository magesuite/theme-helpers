<?php

declare(strict_types=1);

namespace MageSuite\ContentConstructorAdmin\Test\Unit\Helper;

class ImageTypeTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\MageSuite\ThemeHelpers\Helper\ImageType $imageType;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->imageType = $this->objectManager->create(\MageSuite\ThemeHelpers\Helper\ImageType::class);
    }

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
