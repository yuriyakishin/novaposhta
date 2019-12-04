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

    const METHOD_WAREHOUSE = 'novaposhta_to_warehouse';
    const METHOD_DOOR = 'novaposhta_to_door';

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
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
            \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
            \Psr\Log\LoggerInterface $logger,
            \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
            \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,            
            \Magento\Checkout\Model\Session $checkoutSession,
            \Yu\NovaPoshta\Service\Curl $curl,
            \Yu\NovaPoshta\Model\ResourceModel\City $cityResourceModel,
            array $data = []
    )
    {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);

        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->checkoutSession = $checkoutSession;
        $this->cityResourceModel = $cityResourceModel;
        $this->curl = $curl;
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

        $cityRef = '';
        
        if ($this->checkoutSession->getQuote()->getShippingAddress()->getCityNovaposhtaRef()) {
            $cityRef = $this->checkoutSession->getQuote()->getShippingAddress()->getCityNovaposhtaRef();
        } else {

            $cityName = '';

            if ($request->getOrigCity()) {
                $cityName = $request->getOrigCity();
            } elseif ($request->getDestCity()) {
                $cityName = $request->getDestCity();
            }

            $cityRef = $this->cityResourceModel->getRefByName($cityName);
        }


        $citySender = $this->_scopeConfig->getValue(
                'carriers/novaposhta/city_sender',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
        $carrierTitle = $this->_scopeConfig->getValue(
                'carriers/novaposhta/title',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
        $nameWW = $this->_scopeConfig->getValue(
                'carriers/novaposhta/name_ww',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
        $nameWD = $this->_scopeConfig->getValue(
                'carriers/novaposhta/name_wd',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );


        $allowed = $this->getAllowedMethods();

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateResultFactory->create();

        
        /** WarehouseWarehouse  */
        if (in_array(self::METHOD_WAREHOUSE, $allowed)) {
            
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
            ]];

            $price = $this->getNovaPoshtaPrice($params);

            /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
            $methodWarehouse = $this->rateMethodFactory->create();
            $methodWarehouse->setCarrier($this->_code);
            $methodWarehouse->setCarrierTitle($carrierTitle);
            $methodWarehouse->setMethod('novaposhta_to_warehouse');
            $methodWarehouse->setMethodTitle($nameWW);
            $methodWarehouse->setPrice($price);

            $result->append($methodWarehouse);
        }
        

        /** WarehouseDoors  */
        if (in_array(self::METHOD_DOOR, $allowed)) {
            
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
            ]];

            $price = $this->getNovaPoshtaPrice($params);
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
        $allowed = explode(',', $this->getConfigData('allowed_methods'));
        $arr = [];
        /**
        foreach ($allowed as $_code)
        {
            $arr[$_code] = $this->getCode('method', $_code);
        }*/

        return $allowed;
    }

    /**
     * @param array $params
     * @return float|null
     */
    public function getNovaPoshtaPrice($params)
    {
        $data = $this->curl->getDataImport($params);
        
        $cost = '';
        if (isset($data[0]['Cost'])) {
            $cost = $data[0]['Cost'];
        }

        return $cost;
    }

}
