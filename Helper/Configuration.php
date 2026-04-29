<?php

declare(strict_types=1);

namespace MageSuite\ThemeHelpers\Helper;

class Configuration
{
    public const XML_PATH_DESIGN_HEAD_DEFAULT_TITLE = 'design/head/default_title';
    public const XML_PATH_DESIGN_HEAD_TITLE_SUFFIX = 'design/head/title_suffix';

    public function __construct(
        protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        protected \Magento\Framework\Serialize\SerializerInterface $serializer,
        protected \Magento\Framework\View\Page\Config $pageConfig,
    ) {}

    private function getValue(string $path, int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $storeId
        );
    }
    
    public function getPagePrefix(int $storeId): string
    {
        $prefix = $this->pageConfig->getTitle()->getShortHeading();
        $normalized = trim((string)$prefix);

        if ($normalized === '') {
            return $this->getDefaultTitle($storeId);
        }

        return is_string($prefix) ? $prefix : $normalized;
    }

    public function getDefaultTitle(int $storeId): string
    {
        return trim($this->getValue(self::XML_PATH_DESIGN_HEAD_DEFAULT_TITLE, $storeId));
    }

    public function getTitleSuffix(int $storeId): string
    {
        $suffix = trim($this->getValue(self::XML_PATH_DESIGN_HEAD_TITLE_SUFFIX, $storeId));
        return $suffix ? " $suffix" : '';
    }
}
