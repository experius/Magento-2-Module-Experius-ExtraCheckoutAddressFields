<?php
/**
 * Copyright © Experius B.V. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\ExtraCheckoutAddressFields\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

class LayoutProcessor implements LayoutProcessorInterface
{
    protected $helper;

    /**
     * LayoutProcessor constructor.
     *
     * @param \Experius\ExtraCheckoutAddressFields\Helper\Data $helper
     */
    public function __construct(
        \Experius\ExtraCheckoutAddressFields\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param array $result
     * @return array
     */
    public function process($result)
    {
        $result = $this->getShippingFormFields($result);
        $result = $this->getBillingFormFields($result);
        return $result;
    }

    /**
     * @param string $addressType
     * @return array
     */
    public function getAdditionalFields($addressType='shipping')
    {
        if ($addressType=='shipping') {
            return $this->helper->getExtraCheckoutAddressFields('extra_checkout_shipping_address_fields');
        }
        return  $this->helper->getExtraCheckoutAddressFields('extra_checkout_billing_address_fields');
    }

    /**
     * @param $result
     * @return mixed
     */
    public function getShippingFormFields($result)
    {
        if (isset($result['components']['checkout']['children']['steps']['children']
                ['shipping-step']['children']['shippingAddress']['children']
                ['shipping-address-fieldset'])
        ) {
            $shippingPostcodeFields = $this->getFields('shippingAddress.custom_attributes', 'shipping');

            $shippingFields = $result['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'];

            if (isset($shippingFields['street'])) {
                unset($shippingFields['street']['children'][1]['validation']);
                unset($shippingFields['street']['children'][2]['validation']);
            }

            $shippingFields = array_replace_recursive($shippingFields, $shippingPostcodeFields);

            $result['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'] = $shippingFields;
        }

        return $result;
    }

    /**
     * @param $result
     * @return mixed
     */
    public function getBillingFormFields($result)
    {
        if (isset($result['components']['checkout']['children']['steps']['children']
            ['billing-step']['children']['payment']['children']
            ['payments-list'])) {
            $paymentForms = $result['components']['checkout']['children']['steps']['children']
            ['billing-step']['children']['payment']['children']
            ['payments-list']['children'];

            foreach ($paymentForms as $paymentMethodForm => $paymentMethodValue) {
                $paymentMethodCode = str_replace('-form', '', $paymentMethodForm);

                if (!isset($result['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form'])) {
                    continue;
                }

                $billingFields = $result['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children'];

                $billingPostcodeFields = $this->getFields('billingAddress' . $paymentMethodCode . '.custom_attributes', 'billing');

                $billingFields = array_replace_recursive($billingFields, $billingPostcodeFields);

                $result['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children'] = $billingFields;
            }
        }

        return $result;
    }

    /**
     * @param $scope
     * @param $addressType
     * @return array
     */
    public function getFields($scope, $addressType)
    {
        $fields = [];
        foreach ($this->getAdditionalFields($addressType) as $field) {
            $fields[$field] = $this->getField($field, $scope);
        }
        return $fields;
    }

    /**
     * @param $attributeCode
     * @param $scope
     * @return array
     */
    public function getField($attributeCode, $scope)
    {
        $field = [
            'config' => [
                'customScope' => $scope,
            ],
            'dataScope' => $scope . '.' . $attributeCode,
        ];

        return $field;
    }
}
