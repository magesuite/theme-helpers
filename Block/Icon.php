<?php

declare(strict_types=1);
namespace MageSuite\ThemeHelpers\Block;

class Icon extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'MageSuite_ThemeHelpers::icon.phtml';

    public function getIconUrl():string
    {
        $url = $this->getData('icon_url');

        if (substr($url, 0, 4) == 'http') {
            return $url;
        }

        return $this->getViewFileUrl($url);
    }

    public function getIconPath():string
    {
        return (string) $this->getData('icon_url');
    }

    public function getViewFileContents(string $assetPath)
    {
        $asset = $this->_assetRepo->createAsset($assetPath);

        return $asset->getContent();
    }
}
