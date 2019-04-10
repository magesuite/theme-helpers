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

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->assetHelper = $this->objectManager->create(\MageSuite\ThemeHelpers\Helper\Asset::class);
    }

    public function testItReturnsContentOfAnAsset()
    {
        $this->assertEquals('asset_contents', $this->assetHelper->getViewFileContents('MageSuite_ThemeHelpers::images/asset.txt'));
    }

    /**
     * @expectedException \Magento\Framework\View\Asset\File\NotFoundException
     */
    public function testItReturnsNullWhenAssetDoesNotExist()
    {
        $this->assetHelper->getViewFileContents('MageSuite_ThemeHelpers::images/non_existing_asset.txt');
    }
}