<?php

namespace MageSuite\ThemeHelpers\Helper;

/**
 * @deprecated v1.7.1 use assetRepository available in Block class
 */
class Asset extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepository;

    public function __construct(\Magento\Framework\View\Asset\Repository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    public function getViewFileContents($assetPath)
    {
        $asset = $this->assetRepository->createAsset($assetPath);

        return $asset->getContent();
    }
}
