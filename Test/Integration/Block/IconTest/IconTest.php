<?php

declare(strict_types=1);
namespace MageSuite\ThemeHelpers\Test\Integration\Block;

/**
 * @magentoAppArea frontend
 */
class IconTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager = null;
    protected ?\MageSuite\ThemeHelpers\Block\Icon $iconBlock = null;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->iconBlock = $this->objectManager->create(\MageSuite\ThemeHelpers\Block\Icon::class);
        $this->iconBlock->setData('icon_url', 'MageSuite_ThemeHelpers::images/asset.txt');
    }

    public function testItReturnsContentOfAnAsset():void
    {
        $this->assertStringContainsString(
            'asset_contents',
            $this->iconBlock->getViewFileContents($this->iconBlock->getIconPath())
        );
    }

    public function testItReturnsNullWhenAssetDoesNotExist():void
    {
        $this->expectException(\Magento\Framework\View\Asset\File\NotFoundException::class);
        $this->iconBlock->getViewFileContents('MageSuite_ThemeHelpers::images/non_existing_asset.txt');
    }

    public function testItReturnsCorrectIconUrl():void
    {
        $iconUrl = $this->iconBlock->getIconUrl();
        $this->assertStringContainsString(
            'asset.txt',
            $iconUrl
        );
        $this->assertStringContainsString(
            'http',
            $iconUrl
        );
    }
}
