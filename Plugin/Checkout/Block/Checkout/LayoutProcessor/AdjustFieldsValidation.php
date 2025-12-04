<?php

declare(strict_types=1);

namespace MageSuite\ThemeHelpers\Plugin\Checkout\Block\Checkout\LayoutProcessor;

class AdjustFieldsValidation
{
    protected const BILLING_FIELD_PATH_FORMAT = '%s/%s/%s';
    protected const FIRSTNAME_MAX_LENGTH = 255;
    protected const LASTNAME_MAX_LENGTH = 255;
    protected const CITY_MAX_LENGTH = 100;
    protected const STREET_MAX_LENGTH = 255;
    protected const TELEPHONE_MAX_LENGTH = 50;

    public function __construct(
        protected \Magento\Framework\Stdlib\ArrayManager $arrayManager,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
    ) {
    }

    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $subject, array $jsLayout): array
    {
        $jsLayout = $this->addValidationToShippingAddress($jsLayout);
        $jsLayout = $this->addValidationToBillingAddress($jsLayout);

        return $jsLayout;
    }

    protected function addValidationToShippingAddress(array &$jsLayout): array
    {
        $path = 'components/checkout/children/steps/children/shipping-step/children/shippingAddress/children/shipping-address-fieldset/children';
        $fieldset = $this->arrayManager->get($path, $jsLayout);

        if (!$fieldset) {
            return $jsLayout;
        }

        return $this->arrayManager->set($path, $jsLayout, $this->addAddressFieldsetValidation($fieldset));
    }
    
    protected function addValidationToBillingAddress(array &$jsLayout): array
    {
        $paymentMethodsPath = 'components/checkout/children/steps/children/billing-step/children/payment/children/payments-list/children';
        $fieldsPath = 'children/form-fields/children';

        $paymentMethods = $this->arrayManager->get($paymentMethodsPath, $jsLayout);

        if (empty($paymentMethods)) {
            return $jsLayout;
        }

        foreach ($paymentMethods as $paymentCode => $paymentMethod) {
            $fieldset = $this->arrayManager->get($fieldsPath, $paymentMethod);

            if (empty($fieldset)) {
                continue;
            }

            $fullPathCustomization = sprintf(self::BILLING_FIELD_PATH_FORMAT, $paymentMethodsPath, $paymentCode, $fieldsPath);
            $jsLayout = $this->arrayManager->set($fullPathCustomization, $jsLayout, $this->addAddressFieldsetValidation($fieldset));
        }

        return $jsLayout;
    }

    protected function addAddressFieldsetValidation(array $fieldset): array
    {
        $fieldConfigurations = $this->getFieldValidationConfiguration();

        foreach ($fieldConfigurations as $fieldName => $config) {
            if (isset($fieldset[$fieldName])) {
                $fieldset[$fieldName] = $this->applyFieldValidation(
                    $fieldset[$fieldName],
                    $config['validator'],
                    $config['maxLength']
                );
            }
        }

        if (isset($fieldset['street']['children'])) {
            $fieldset['street']['children'] = $this->applyStreetValidation($fieldset['street']['children']);
        }

        return $fieldset;
    }

    protected function getFieldValidationConfiguration(): array
    {
        return [
            'firstname' => [
                'validator' => 'validate-name',
                'maxLength' => self::FIRSTNAME_MAX_LENGTH,
            ],
            'lastname' => [
                'validator' => 'validate-name',
                'maxLength' => self::LASTNAME_MAX_LENGTH,
            ],
            'city' => [
                'validator' => 'validate-city',
                'maxLength' => self::CITY_MAX_LENGTH,
            ],
            'telephone' => [
                'validator' => 'validate-phone',
                'maxLength' => self::TELEPHONE_MAX_LENGTH,
            ],
        ];
    }

    protected function applyFieldValidation(array $field, string $validator, int $maxLength): array
    {
        $field['validation'] = [
            $validator => true,
            'max_text_length' => $maxLength,
        ];
        $field['config']['maxlength'] = $maxLength;

        return $field;
    }

    protected function applyStreetValidation(array $streetChildren): array
    {
        foreach ($streetChildren as $index => $streetLine) {
            $streetChildren[$index]['validation'] = [
                'validate-street' => true,
                'max_text_length' => self::STREET_MAX_LENGTH,
            ];
            $streetChildren[$index]['config']['maxlength'] = self::STREET_MAX_LENGTH;
        }

        return $streetChildren;
    }
}
