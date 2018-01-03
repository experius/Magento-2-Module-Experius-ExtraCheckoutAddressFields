<?php


namespace Experius\ExtraCheckoutAddressFields\Plugin\Magento\Checkout\Model;

class PaymentInformationManagement
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

    public function beforeSavePaymentInformation(
        \Magento\Checkout\Model\PaymentInformationManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\AddressInterface $address
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