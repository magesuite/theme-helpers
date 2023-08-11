<?php

declare(strict_types=1);
namespace MageSuite\ThemeHelpers\Test\Integration\Helper;

/**
 * @magentoAppArea frontend
 */
class AssetTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager = null;
    protected ?\MageSuite\ThemeHelpers\Helper\Asset $assetHelper = null;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->assetHelper = $this->objectManager->create(\MageSuite\ThemeHelpers\Helper\Asset::class);
    }

    public function testItReturnsContentOfAnAsset()
    {
        $this->assertStringContainsString('asset_contents', $this->assetHelper->getViewFileContents('MageSuite_ThemeHelpers::images/asset.txt'));
    }

    public function testItReturnsNullWhenAssetDoesNotExist()
    {
        $this->expectException(\Magento\Framework\View\Asset\File\NotFoundException::class);
        $this->assetHelper->getViewFileContents('MageSuite_ThemeHelpers::images/non_existing_asset.txt');
    }
}
