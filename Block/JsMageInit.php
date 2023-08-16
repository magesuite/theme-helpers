<?php

namespace MageSuite\ThemeHelpers\Block;

class JsMageInit extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'MageSuite_ThemeHelpers::js-mage-init.phtml'; // phpcs:ignore

    public function getJsConfig():string
    {
        $config = $this->getData('js_config');

        return $config ? json_encode($config) : '{}';
    }
}
