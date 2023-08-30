<?php

namespace MageSuite\ThemeHelpers\Block\Category\View;

class Headline extends \Magento\Framework\View\Element\Template
{
    public const DEFAULT_HTML_TAG = 'h1';

    public const ATTRIBUTE_HEADLINE_HTML_TAG = 'headline_html_tag';
    public const ATTRIBUTE_HIDE_HEADLINE = 'hide_headline';

    protected \Magento\Framework\View\Page\Title $pageTitle;
    protected \Magento\Catalog\Model\Layer\Resolver $layerResolver;
    protected \Magento\Framework\Registry $registry;
    protected \MageSuite\CategoryIcon\Helper\CategoryIcon $categoryIconHelper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\View\Page\Title $pageTitle,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\Registry $registry,
        \MageSuite\CategoryIcon\Helper\CategoryIcon $categoryIconHelper,
        array $data = []
    ) {
        $this->pageTitle = $pageTitle;
        $this->layerResolver = $layerResolver;
        $this->registry = $registry;
        $this->categoryIconHelper = $categoryIconHelper;

        parent::__construct($context, $data);
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getIcon(): ?string
    {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->registry->registry('current_category');

        if (empty($category)) {
            return null;
        }

        return $this->categoryIconHelper->getUrl($category);
    }

    public function shouldHeadlineBeHidden(): bool
    {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->registry->registry('current_category');

        return (bool)$category->getData(self::ATTRIBUTE_HIDE_HEADLINE);
    }

    public function getHeadline(): string
    {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->registry->registry('current_category');

        if (empty($category)) {
            return $this->pageTitle->getShort();
        } else {
            return $category->getName();
        }
    }

    public function getHeadlineHtmlTag(): string
    {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->registry->registry('current_category');

        return $category->getData(self::ATTRIBUTE_HEADLINE_HTML_TAG) ?? self::DEFAULT_HTML_TAG;
    }

    public function getCollectionSize(): int
    {
        $layer = $this->layerResolver->get();
        $collection = $layer->getProductCollection();

        return $collection->getSize();
    }
}
