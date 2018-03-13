<?php


namespace Experius\ExtraCheckoutAddressFields\Observer\Sales;

class ModelServiceQuoteSubmitBefore implements \Magento\Framework\Event\ObserverInterface
{

    protected $helper;

    protected $logger;

    protected $quoteRepository;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Psr\Log\LoggerInterface $logger,
        \Experius\ExtraCheckoutAddressFields\Helper\Data $helper
    )
    {
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {

        /** @var \Magento\Sales\Model\Order $order */
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