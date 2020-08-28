<?php
namespace MageSuite\ThemeHelpers\Test\Integration\Helper;

/**
 * @magentoAppArea frontend
 */
class AssetTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\ThemeHelpers\Helper\Asset
     */
    protected $assetHelper;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->assetHelper = $this->objectManager->create(\MageSuite\ThemeHelpers\Helper\Asset::class);
    }

    public function testItReturnsContentOfAnAsset()
    {
        $this->assertEquals('asset_contents', $this->assetHelper->getViewFileContents('MageSuite_ThemeHelpers::images/asset.txt'));
    }

    public function testItReturnsNullWhenAssetDoesNotExist()
    {
        $this->expectException(\Magento\Framework\View\Asset\File\NotFoundException::class);
        $this->assetHelper->getViewFileContents('MageSuite_ThemeHelpers::images/non_existing_asset.txt');
    }
}
