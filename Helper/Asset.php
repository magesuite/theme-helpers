<?php

declare(strict_types=1);
namespace MageSuite\ThemeHelpers\Helper;

class Asset extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected \Magento\Framework\View\Asset\Repository $assetRepository;

    public function __construct(
        \Magento\Framework\View\Asset\Repository $assetRepository
    ) {
        $this->assetRepository = $assetRepository;
    }

    public function getViewFileContents($assetPath):string
    {
        $asset = $this->assetRepository->createAsset($assetPath);

        return $asset->getContent();
    }
}
