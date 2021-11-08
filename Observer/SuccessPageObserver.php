<?php


namespace Magento\KlashaPaymentGateway\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magento\KlashaPaymentGateway\Model\Ui\ConfigProvider;


class SuccessPageObserver implements ObserverInterface
{
    /** @var \Magento\Framework\Logger\Monolog */
    protected $_logger;

    /**
     * @var \Magento\Framework\ObjectManager\ObjectManager
     */
    protected $_objectManager;

    protected $_orderFactory;
    protected $_checkoutSession;

    public function __construct(
        \Psr\Log\LoggerInterface $loggerInterface,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\ObjectManager\ObjectManager $objectManager
    )
    {
        $this->_logger = $loggerInterface;
        $this->_objectManager = $objectManager;
        $this->_orderFactory = $orderFactory;
        $this->_checkoutSession = $checkoutSession;
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();

        if (count($orderIds)) {
            $orderId = $orderIds[0];
            $order = $this->_orderFactory->create()->load($orderId);
            $method = $order->getPayment()->getMethodInstance();
            // if order payment method is klasha
            if ($method->getCode() === ConfigProvider::CODE) {
                // set the order status to 'successful_payment'
                $order->setStatus(Order::STATE_PROCESSING);
                $order->save();
            }
            // do something
            // your code goes here

           // $this->logger->debug('Logging HelloWorld Observer');
        }
    }

}
