<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\KlashaPaymentGateway\Model\Ui;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Store\Model\Store as Store;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\KlashaPaymentGateway\Gateway\Http\Client\ClientMock;

/**
 * Class ConfigProvider
 */
class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'sample_gateway';

    public function __construct(PaymentHelper $paymentHelper, Store $store)
    {
        $this->method = $paymentHelper->getMethodInstance(self::CODE);
        $this->store = $store;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        $endpoint = "https://ktests.com/";
        $server_mode = "demo";

        if ($this->method->getConfigData('go_live')) {
            $endpoint = "https://gate.klasapps.com/";
            $server_mode = "live";
        }
        return [
            'payment' => [
                self::CODE => [
                   /* 'transactionResults' => [
                        ClientMock::SUCCESS => __('Success'),
                        ClientMock::FAILURE => __('Fraud')
                    ]*/
                    'endpoint' => $endpoint,
                    'MID' => $this->method->getConfigData('client_mid'),
                    'failed_page_url' => $this->store->getBaseUrl() . 'checkout/onepage/failure',
                    'mode' => $server_mode
                ]
            ]
        ];
    }
}
