<?php
declare(strict_types=1);

namespace MageSuite\ThemeHelpers\ViewModel;

class Image implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected \Magento\Catalog\Helper\Image $imageHelper;

    public function __construct(\Magento\Catalog\Helper\Image $imageHelper)
    {
        $this->imageHelper = $imageHelper;
    }

    /**
     * Available attributes:
     * - 'type' => string
     * - 'width' => int
     * - 'height' => int
     * - 'frame' => int
     * - 'aspect_ratio' => bool
     * - 'transparency' => bool
     * - 'constrain' => bool
     * - 'aspect_ratio' => bool
     * - 'keep_frame' => bool
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param string $imageId
     * @param array $attributes
     * @return string
     */
    public function getImageUrl(\Magento\Catalog\Api\Data\ProductInterface $product, $imageId, array $attributes = [])
    {
        return $this->imageHelper->init($product, $imageId, $attributes)->getUrl();
    }
}
