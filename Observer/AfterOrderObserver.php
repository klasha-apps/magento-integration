<?php

namespace Magento\KlashaPaymentGateway\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magento\KlashaPaymentGateway\Model\Ui\ConfigProvider;


class AfterOrderObserver implements ObserverInterface
{

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //Observer execution code...
        $order = $observer->getEvent()->getOrder();
        // get the payment method instance
        $method = $order->getPayment()->getMethodInstance();
        // if order payment method is interswitch
        if ($method->getCode() === ConfigProvider::CODE) {
            // set the order status to 'pending_payment'
            $order->setStatus(Order::STATE_PENDING_PAYMENT);
            $order->save();
        }
    }
}
