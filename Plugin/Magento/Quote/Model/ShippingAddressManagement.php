<?php


namespace Experius\ExtraCheckoutAddressFields\Plugin\Magento\Quote\Model;

class ShippingAddressManagement
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
        \Magento\Quote\Model\ShippingAddressManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\AddressInterface $address
    ) {

        $extAttributes = $address->getExtensionAttributes();

        if (!empty($extAttributes)) {

            foreach($this->helper->getExtraCheckoutAddressFields('extra_checkout_shipping_address_fields') as $extraField) {

                $set = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $extraField)));
                $get = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $extraField)));

                $value = $extAttributes->$get();
                try {
                    $address->$set($value);
                } catch (\Exception $e) {
                    $this->logger->critical($e->getMessage());
                }
            }
        }

    }
}