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

        foreach($this->helper->getExtraCheckoutAddressFields('extra_checkout_billing_address_fields') as $extraField) {
            $set = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $extraField)));
            $get = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $extraField)));

            try {
                $order->getBillingAddress()->$set($quote->getBillingAddress()->$get())->save();
            } catch (\Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }

        foreach($this->helper->getExtraCheckoutAddressFields('extra_checkout_shipping_address_fields') as $extraField) {
            $set = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $extraField)));
            $get = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $extraField)));

            try {
                $order->getShippingAddress()->$set($quote->getShippingAddress()->$get())->save();
            } catch (\Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }

    }
}