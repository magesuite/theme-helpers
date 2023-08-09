<?php

namespace MageSuite\ThemeHelpers\Block;

class Icon extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'MageSuite_ThemeHelpers::icon.phtml';

    public function getIconUrl()
    {
        $url = $this->getData('icon_url');

        if (substr($url, 0, 4) == 'http') {
            return $url;
        }

        return $this->getViewFileUrl($url);
    }
}
