<?php

namespace Experius\ExtraCheckoutAddressFields\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper{

    protected $fieldsetConfig;

    protected $logger;

    public function __construct(
        \Magento\Framework\DataObject\Copy\Config $fieldsetConfig,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->fieldsetConfig = $fieldsetConfig;
        $this->logger = $logger;
    }

    public function getExtraCheckoutAddressFields($fieldset='extra_checkout_billing_address_fields',$root='global'){

        $fields = $this->fieldsetConfig->getFieldset($fieldset, $root);

        $extraCheckoutFields = [];

        foreach($fields as $field=>$fieldInfo){
            $extraCheckoutFields[] = $field;
        }

        return $extraCheckoutFields;

    }
}