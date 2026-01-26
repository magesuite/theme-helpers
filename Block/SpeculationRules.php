<?php

declare(strict_types=1);

namespace MageSuite\ThemeHelpers\Block;

class SpeculationRules extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        protected \MageSuite\ThemeHelpers\Helper\Configuration $configuration,
        protected \Magento\Framework\View\Helper\SecureHtmlRenderer $secureRenderer,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    protected function _toHtml(): string
    {
        $storeId = (int)$this->_storeManager->getStore()->getId();
        $speculationRulesConfig = $this->configuration->getSpeculationRulesConfiguration($storeId);

        if (!$speculationRulesConfig || $speculationRulesConfig === '') {
            return '';
        }

        $script = $this->secureRenderer->renderTag(
            'script',
            ['type' => 'speculationrules'],
            $speculationRulesConfig,
            false
        );

        return $script ?? '';
    }
}
