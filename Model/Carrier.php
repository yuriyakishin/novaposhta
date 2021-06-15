<?php

namespace Yu\NovaPoshta\Model;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;

/**
 * NovaPoshta shipping model
 */
class Carrier extends AbstractCarrier implements CarrierInterface
{
    private const METHOD_WAREHOUSE = 'novaposhta_to_warehouse';
    private const METHOD_DOOR = 'novaposhta_to_door';

    /**
     * @var string
     */
    protected $_code = 'novaposhta';

    /**
     * @var bool
     */
    protected $_isFixed = false;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    private $rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    private $rateMethodFactory;

    /**
     * @var \Magento\Shipping\Model\Tracking\Result\StatusFactory
     */
    protected $trackResultFactory;

    /**
     * @var \Yu\NovaPoshta\Service\Curl
     */
    private $curl;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var \Yu\NovaPoshta\Model\ResourceModel\City
     */
    private $cityResourceModel;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface          $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory  $rateErrorFactory
     * @param \Psr\Log\LoggerInterface                                    $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory                  $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param \Magento\Shipping\Model\Tracking\Result\StatusFactory       $trackResultFactory
     * @param \Magento\Checkout\Model\Session                             $checkoutSession
     * @param \Yu\NovaPoshta\Service\Curl                                 $curl
     * @param ResourceModel\City                                          $cityResourceModel
     * @param Config                                                      $config
     * @param array                                                       $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackResultFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Yu\NovaPoshta\Service\Curl $curl,
        \Yu\NovaPoshta\Model\ResourceModel\City $cityResourceModel,
        \Yu\NovaPoshta\Model\Config $config,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);

        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->trackResultFactory = $trackResultFactory;
        $this->checkoutSession = $checkoutSession;
        $this->cityResourceModel = $cityResourceModel;
        $this->curl = $curl;
        $this->config = $config;
    }

    /**
     * NovaPoshta Rates Collector
     *
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result|bool
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $cityName = '';

        if ($request->getOrigCity()) {
            $cityName = $request->getOrigCity();
        } elseif ($request->getDestCity()) {
            $cityName = $request->getDestCity();
        }

        $cityRef = $this->cityResourceModel->getRefByName($cityName);

        /* crutch */
        if (empty($cityRef) && $this->checkoutSession->getQuote()->getShippingAddress()->getCityNovaposhtaRef()) {
            $cityRef = $this->checkoutSession->getQuote()->getShippingAddress()->getCityNovaposhtaRef();
        }

        $citySender = $this->config->getCitySender();
        $carrierTitle = $this->config->getCarrierTitle();
        $nameWW = $this->config->getNameWarehouseToWarehouse();
        $nameWD = $this->config->getNameWarehouseToDoors();

        $allowed = $this->getAllowedMethods();

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateResultFactory->create();

        /** WarehouseWarehouse  */
        if (in_array(self::METHOD_WAREHOUSE, $allowed, true)) {

            $customPrice = $this->getCustomPrice('warehouse');

            $params = [
                'modelName'        => 'InternetDocument',
                'calledMethod'     => 'getDocumentPrice',
                'apiKey'           => '',
                'methodProperties' => [
                    'CitySender'    => $citySender,
                    'CityRecipient' => $cityRef,
                    'Weight'        => $request->getPackageWeight(),
                    'ServiceType'   => 'WarehouseWarehouse',
                    'Cost'          => $this->checkoutSession->getQuote()->getBaseSubtotal(),
                    'CargoType'     => 'Cargo',
                    'SeatsAmount'   => 1,
                ]
            ];

            $price = $customPrice ?? $this->getNovaPoshtaPrice($params);

            /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $methodWarehouse */
            $methodWarehouse = $this->rateMethodFactory->create();
            $methodWarehouse->setCarrier($this->_code);
            $methodWarehouse->setCarrierTitle($carrierTitle);
            $methodWarehouse->setMethod('novaposhta_to_warehouse');
            $methodWarehouse->setMethodTitle($nameWW);
            $methodWarehouse->setPrice($price);

            $result->append($methodWarehouse);
        }

        /** WarehouseDoors  */
        if (in_array(self::METHOD_DOOR, $allowed, true)) {

            $customPrice = $this->getCustomPrice('door');

            $params = [
                'modelName'        => 'InternetDocument',
                'calledMethod'     => 'getDocumentPrice',
                'apiKey'           => '',
                'methodProperties' => [
                    'CitySender'    => $citySender,
                    'CityRecipient' => $cityRef,
                    'Weight'        => $request->getPackageWeight(),
                    'ServiceType'   => 'WarehouseDoors',
                    'Cost'          => $this->checkoutSession->getQuote()->getBaseSubtotal(),
                    'CargoType'     => 'Cargo',
                    'SeatsAmount'   => 1,
                ]
            ];

            $price = $customPrice ?? $this->getNovaPoshtaPrice($params);

            /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $methodDoor */
            $methodDoor = $this->rateMethodFactory->create();
            $methodDoor->setCarrier($this->_code);
            $methodDoor->setCarrierTitle($carrierTitle);
            $methodDoor->setMethod('novaposhta_to_door');
            $methodDoor->setMethodTitle($nameWD);
            $methodDoor->setPrice($price);

            $result->append($methodDoor);
        }

        return $result;
    }

    /**
     * Get configuration data of carrier
     *
     * @param string $type
     * @param string $code
     * @return array|false
     */
    public function getCode($type, $code = '')
    {
        $codes = [
            'method' => [
                self::METHOD_WAREHOUSE => __('До склада'),
                self::METHOD_DOOR      => __('До дверей'),
            ],
        ];

        if (!isset($codes[$type])) {
            return false;
        } elseif ('' === $code) {
            return $codes[$type];
        }

        if (!isset($codes[$type][$code])) {
            return false;
        } else {
            return $codes[$type][$code];
        }

        return false;
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return explode(',', $this->getConfigData('allowed_methods'));
    }

    /**
     * Get custom price from system config.
     * $type = 'warehouse' or 'door'
     *
     * @param string $type
     * @return float|null
     */
    public function getCustomPrice($type)
    {
        $price = -1;

        /** fix price */
        if (!empty($this->getConfigData('price_fix_'.$type))) {
            $price = $this->getConfigData('price_fix_'.$type);
        }

        /** free amount */
        if (!empty($this->getConfigData('amount_free_'.$type))) {

            $amountFree = (float) $this->getConfigData('amount_free_'.$type);
            if ($amountFree <= $this->checkoutSession->getQuote()->getGrandTotal()) {
                $price = 0;
            }
        }

        if ($price !== -1) {
            return $price;
        }

        return null;
    }

    /**
     * @param array $params
     * @return float|null
     */
    public function getNovaPoshtaPrice($params)
    {
        if (empty($params['methodProperties']['Weight'])) {
            $params['methodProperties']['Weight'] = 1;
        }

        $data = $this->curl->getDataImport($params);

        $cost = '';
        if (isset($data[0]['Cost'])) {
            $cost = $data[0]['Cost'];
        }

        return $cost;
    }

    /**
     * Check if tracking info is available
     * @return boolean
     */
    public function isTrackingAvailable()
    {
        return true;
    }

    /**
     * Get tracking information
     *
     * @param string $trackNumber
     * @return \Magento\Shipping\Model\Tracking\Result\Status
     * @api
     */
    public function getTrackingInfo($trackNumber)
    {
        $title = $this->getConfigData('title');
        $url = 'https://novaposhta.ua/tracking/index/cargo_number/' . $trackNumber . '/no_redirect/1';
        return $this->trackResultFactory->create()->setCarrierTitle($title)->setTracking($trackNumber)->setUrl($url);
    }
}
