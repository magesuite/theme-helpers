<?php

declare(strict_types=1);

namespace MageSuite\ThemeHelpers\Plugin\Magento\Framework\Mail\Template\TransportBuilder;

class AddLocaleVariableToEmailTemplates
{
    protected CONST XML_PATH_LOCALE_CODE = 'general/locale/code';

    public function __construct(
        protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
    }

    public function beforeSetTemplateVars(
        \Magento\Framework\Mail\Template\TransportBuilder $subject,
        array $variables
    ): array {
        $locale = $this->scopeConfig->getValue(self::XML_PATH_LOCALE_CODE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $variables['locale'] = str_replace('_', '-', $locale);

        return [$variables];
    }
}
