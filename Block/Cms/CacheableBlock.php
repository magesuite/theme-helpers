<?php

namespace MageSuite\ThemeHelpers\Block\Cms;

class CacheableBlock extends \Magento\Cms\Block\Block
{
    const ONE_DAY = 86400;

    /**
     * @return int
     */
    public function getCacheLifetime()
    {
        return self::ONE_DAY;
    }

    /**
     * @inheritdoc
     */
    public function getCacheKeyInfo()
    {
        return [
            $this->getBlockId(),
            $this->_storeManager->getStore()->getId()
        ];
    }
}