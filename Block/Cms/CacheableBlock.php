<?php

namespace MageSuite\ThemeHelpers\Block\Cms;

class CacheableBlock extends \Magento\Cms\Block\Block
{
    const ONE_DAY = 86400;

    /**
     * @var \Magento\Cms\Model\Block
     */
    protected $blockModel;

    /**
     * @var \Magento\Cms\Model\ResourceModel\Block
     */
    protected $blockResource;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Cms\Model\ResourceModel\Block $blockResource,
        array $data = []
    ) {
        $this->blockResource = $blockResource;
        parent::__construct($context, $filterProvider, $storeManager, $blockFactory, $data);
    }

    protected function _toHtml()
    {
        $blockId = $this->getBlockId();
        $html = '';

        if (!$blockId) {
            return $html;
        }

        $storeId = $this->_storeManager->getStore()->getId();

        $this->blockModel = $this->_blockFactory->create();
        $this->blockModel->setStoreId($storeId);
        $this->blockResource->load($this->blockModel, $blockId);

        if ($this->blockModel->isActive()) {
            $html = $this->_filterProvider->getBlockFilter()
                ->setStoreId($storeId)
                ->filter($this->blockModel->getContent());
        }

        return $html;
    }

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

    public function getIdentities()
    {
        $blockIdCacheTag = parent::getIdentities();

        if ($this->blockModel) {
            return array_merge(
                $blockIdCacheTag,
                $this->blockModel->getIdentities()
            );
        }

        return $blockIdCacheTag;
    }
}
