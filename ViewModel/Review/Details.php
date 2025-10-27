<?php

declare(strict_types=1);

namespace MageSuite\ThemeHelpers\ViewModel\Review;

class Details implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    public function __construct(
        protected \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableType,
        protected \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
    ) {}

    public function getProductUrl(\Magento\Catalog\Model\Product $product): string
    {
        if ($product->getTypeId() !== \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
            return $product->getProductUrl();
        }

        $parentIds = $this->configurableType->getParentIdsByChild($product->getId());

        if (empty($parentIds)) {
            return $product->getProductUrl();
        }

        $parentId = current($parentIds);

        try {
            return $this->productRepository->getById($parentId)->getProductUrl();
        } catch (\Magento\Framework\Exception\NoSuchEntityException) {
            return $product->getProductUrl();
        }
    }
}
