<?php

namespace MageSuite\ThemeHelpers\Block\Category\View;

class Headline extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    /**
     * @var \MageSuite\CategoryIcon\Helper\CategoryIcon
     */
    protected $categoryIconHelper;

    /**
     * @var \Magento\Framework\View\Page\Title
     */
    protected $pageTitle;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $layerResolver;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\View\Page\Title $pageTitle,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\Registry $registry,
        \MageSuite\CategoryIcon\Helper\CategoryIcon $categoryIcon,
        array $data = []
    )
    {
        $this->registry = $registry;
        $this->pageTitle = $pageTitle;
        $this->layerResolver = $layerResolver;
        $this->categoryIconHelper = $categoryIcon;

        parent::__construct($context, $data);
    }

    public function getIcon() {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->registry->registry('current_category');

        if (empty($category)) {
            return null;
        }
        
        return $this->categoryIconHelper->getUrl($category);
    }
    
    public function getHeadline()
    {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->registry->registry('current_category');

        if (empty($category)) {
            return $this->pageTitle->getShort();
        } else {
            return $category->getName();
        }
    }

    public function getCollectionSize()
    {
        $layer = $this->layerResolver->get();
        $collection = $layer->getProductCollection();

        return $collection->getSize();
    }
}