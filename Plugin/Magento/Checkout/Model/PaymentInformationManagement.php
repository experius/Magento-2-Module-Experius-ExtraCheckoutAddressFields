<?php
/**
 * Copyright © Experius B.V. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\ExtraCheckoutAddressFields\Plugin\Magento\Checkout\Model;

use Experius\ExtraCheckoutAddressFields\Helper\Data;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;

class PaymentInformationManagement
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * PaymentInformationManagement constructor.
     *
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    )
    {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Checkout\Model\PaymentInformationManagement $subject
     * @param $cartId
     * @param PaymentInterface $paymentMethod
     * @param AddressInterface $address
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSavePaymentInformation(
        \Magento\Checkout\Model\PaymentInformationManagement $subject,
                                                             $cartId,
        PaymentInterface                                     $paymentMethod,
        AddressInterface                                     $address = null
    )
    {
        if ($address) {
            $extAttributes = $address->getExtensionAttributes();
            if (!empty($extAttributes)) {
                $this->helper->transportFieldsFromExtensionAttributesToObject(
                    $extAttributes,
                    $address,
                    'extra_checkout_billing_address_fields'
                );
            }
        }
    }
}
