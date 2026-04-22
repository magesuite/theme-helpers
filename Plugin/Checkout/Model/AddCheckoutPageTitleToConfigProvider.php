<?php

declare(strict_types=1);

namespace MageSuite\ThemeHelpers\Plugin\Checkout\Model;

class AddCheckoutPageTitleToConfigProvider
{
    public function __construct(
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \MageSuite\ThemeHelpers\Helper\Configuration $configuration,
    ) {}

    public function afterGetConfig(\Magento\Checkout\Model\DefaultConfigProvider $subject, array $result): array
    {
        $storeId = (int)$this->storeManager->getStore()->getId();
        $result['checkoutPageTitle'] = [
            'prefix' => $this->configuration->getPagePrefix($storeId),
            'suffix' => $this->configuration->getTitleSuffix($storeId),
        ];

        return $result;
    }
}
