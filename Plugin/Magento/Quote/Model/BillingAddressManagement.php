<?php


namespace Experius\ExtraCheckoutAddressFields\Plugin\Magento\Quote\Model;

class BillingAddressManagement
{

    protected $helper;

    protected $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Experius\ExtraCheckoutAddressFields\Helper\Data $helper
    ) {
        $this->logger = $logger;
        $this->helper = $helper;
    }

    public function beforeAssign(
        \Magento\Quote\Model\BillingAddressManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\AddressInterface $address,
        $useForShipping = false
    ) {

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