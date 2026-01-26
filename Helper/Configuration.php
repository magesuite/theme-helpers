<?php

declare(strict_types=1);

namespace MageSuite\ThemeHelpers\Helper;

class Configuration
{
    public const XML_PATH_SPECULATION_RULES_CONFIGURATION = 'speculation_rules/general/configuration';

    public function __construct(
        protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        protected \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
    }

    private function getValue(string $path, int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $storeId
        );
    }

    public function getSpeculationRulesConfiguration(int $storeId): string
    {
        return $this->getValue(self::XML_PATH_SPECULATION_RULES_CONFIGURATION, $storeId);
    }
}
