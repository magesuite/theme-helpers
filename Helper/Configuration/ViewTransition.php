<?php

declare(strict_types=1);

namespace MageSuite\ThemeHelpers\Helper\Configuration;

class ViewTransition
{
    public const XML_PATH_IS_ENABLED = 'view_transition/general/is_enabled';
    public const XML_PATH_PRODUCT_IMAGE_IS_ENABLED = 'view_transition/product_image_transition/is_enabled';
    public const XML_PATH_PRODUCT_IMAGE_GALLERY_SELECTOR = 'view_transition/product_image_transition/gallery_selector';
    public const XML_PATH_PRODUCT_IMAGE_GALLERY_LEAVE_SELECTOR = 'view_transition/product_image_transition/gallery_leave_selector';
    public const XML_PATH_PRODUCT_IMAGE_TILE_LINK_SELECTOR = 'view_transition/product_image_transition/tile_link_selector';
    public const XML_PATH_PRODUCT_IMAGE_TILE_IMAGE_SELECTOR = 'view_transition/product_image_transition/tile_image_selector';
    public const XML_PATH_PRODUCT_IMAGE_DURATION = 'view_transition/product_image_transition/duration';

    public function __construct(
        protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
    }

    public function isEnabled(int $storeId): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_IS_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function isProductImageTransitionEnabled(int $storeId): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_PRODUCT_IMAGE_IS_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getProductImageGallerySelector(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_PRODUCT_IMAGE_GALLERY_SELECTOR, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getProductImageGalleryLeaveSelector(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_PRODUCT_IMAGE_GALLERY_LEAVE_SELECTOR, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getProductImageTileLinkSelector(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_PRODUCT_IMAGE_TILE_LINK_SELECTOR, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getProductImageTileImageSelector(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_PRODUCT_IMAGE_TILE_IMAGE_SELECTOR, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getProductImageDuration(int $storeId): int
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_PRODUCT_IMAGE_DURATION, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
}
