<?php

declare(strict_types=1);

namespace MageSuite\ContentConstructorAdmin\Test\Unit\Helper;

class ImageTypeTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\MageSuite\ThemeHelpers\Helper\ImageType $imageType;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected ?\PHPUnit\Framework\MockObject\MockObject $scopeConfig;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->scopeConfig = $this->getMockBuilder(\Magento\Framework\App\Config\ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scopeConfig->method('getValue')->willReturn(true);

        $this->imageType = $this->objectManager->create(\MageSuite\ThemeHelpers\Helper\ImageType::class, ['scopeConfig' => $this->scopeConfig]);
    }

    /**
     * @dataProvider getWebpUrls
     */
    public function testItResolvesWebpSupport(string $path, bool $expectedResult): void
    {
        $this->assertEquals($expectedResult, $this->imageType->supportsWebp($path));
    }

    public static function getWebpUrls(): array
    {
        return [
            ['/var/images/image.jpg', true],
            ['https://www.example.com/image.jpg', true],
            ['https://www.example.com/image.jpeg', true],
            ['https://www.example.com/image.png', true],
            ['https://www.example.com/image.PNG', true],
            ['https://www.example.com/image.gif', false],
        ];
    }

    /**
     * @dataProvider getMimeTypeUrls
     */
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
