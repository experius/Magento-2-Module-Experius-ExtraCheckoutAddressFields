<?php
/**
 * Copyright © Experius B.V. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Experius\ExtraCheckoutAddressFields\Observer\Sales;

use Experius\ExtraCheckoutAddressFields\Helper\Data;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;

class ModelServiceQuoteSubmitBefore implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var QuoteRepository
     */
    protected $quoteRepository;

    /**
     * ModelServiceQuoteSubmitBefore constructor.
     *
     * @param QuoteRepository $quoteRepository
     * @param LoggerInterface $logger
     * @param Data $helper
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        LoggerInterface $logger,
        Data $helper
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {

        /** @var Order $order */
        $order = $observer->getOrder();

        $quote = $this->quoteRepository->get($order->getQuoteId());

        $this->helper->transportFieldsFromExtensionAttributesToObject(
            $quote->getBillingAddress(),
            $order->getBillingAddress(),
            'extra_checkout_billing_address_fields'
        );

        if ($order->getShippingAddress()) {
            $this->helper->transportFieldsFromExtensionAttributesToObject(
                $quote->getShippingAddress(),
                $order->getShippingAddress(),
                'extra_checkout_shipping_address_fields'
            );
        }
    }
}
