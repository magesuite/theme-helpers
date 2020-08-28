<?php

namespace MageSuite\ContentConstructorAdmin\Test\Unit\Helper;

class ImageTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\ThemeHelpers\Helper\ImageType
     */
    protected $imageType;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function setUp(): void {
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
    public function testItResolvesWebpSupport($path, $expectedResult) {
        $this->assertEquals($expectedResult, $this->imageType->supportsWebp($path));
    }

    public static function getWebpUrls() {
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
    public function testItResolvesMimeTypes($path, $expectedResult) {
        $this->assertEquals($expectedResult, $this->imageType->getMimeType($path));
    }

    public static function getMimeTypeUrls() {
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
