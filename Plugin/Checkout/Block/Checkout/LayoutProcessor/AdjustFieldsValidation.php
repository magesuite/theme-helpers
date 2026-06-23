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
        if (isset($fieldset['firstname'])) {
            $fieldset['firstname']['validation'] = array_merge(
                $fieldset['firstname']['validation'] ?? [],
                [
                    'validate-name' => true,
                    'max_text_length' => self::FIRSTNAME_MAX_LENGTH,
                ]
            );
            $fieldset['firstname']['config']['maxlength'] = self::FIRSTNAME_MAX_LENGTH;
        }

        if (isset($fieldset['lastname'])) {
            $fieldset['lastname']['validation'] = array_merge(
                $fieldset['lastname']['validation'] ?? [],
                [
                    'validate-name' => true,
                    'max_text_length' => self::LASTNAME_MAX_LENGTH,
                ]
            );
            $fieldset['lastname']['config']['maxlength'] = self::LASTNAME_MAX_LENGTH;
        }

        if (isset($fieldset['city'])) {
            $fieldset['city']['validation'] = array_merge(
                $fieldset['city']['validation'] ?? [],
                [
                    'validate-city' => true,
                    'max_text_length' => self::CITY_MAX_LENGTH,
                ]
            );
            $fieldset['city']['config']['maxlength'] = self::CITY_MAX_LENGTH;
        }

        if (isset($fieldset['street']['children'])) {
            foreach ($fieldset['street']['children'] as $index => $streetLine) {
                $streetLine['validation']['validate-street-name'] = true;
                $streetLine['validation']['max_text_length'] = self::STREET_MAX_LENGTH;
                $streetLine['maxlength'] = self::STREET_MAX_LENGTH;
                $fieldset['street']['children'][$index] = $streetLine;
            }
        }

        if (isset($fieldset['telephone'])) {
            $fieldset['telephone']['validation'] = array_merge(
                $fieldset['telephone']['validation'] ?? [],
                [
                    'validate-phone' => true,
                    'max_text_length' => self::TELEPHONE_MAX_LENGTH,
                ]
            );
            $fieldset['telephone']['config']['maxlength'] = self::TELEPHONE_MAX_LENGTH;
        }

        return $fieldset;
    }
}
