<?php

namespace Yu\NovaPoshta\Block\Checkout;

/**
 * Класс добавляет список городов в jsLayout
 */
class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{

    /**
     * @var \Yu\NovaPoshta\Api\CityRepositoryInterface
     */
    private $cityRepository;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder 
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var sting 
     */
    private $lang;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Yu\NovaPoshta\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory
     */
    public function __construct(
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
            \Magento\Checkout\Model\Session $checkoutSession,
            \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
            \Yu\NovaPoshta\Api\CityRepositoryInterface $cityRepository
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->cityRepository = $cityRepository;
        $this->checkoutSession = $checkoutSession;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->lang = $this->scopeConfig->getValue(
                'carriers/novaposhta/lang',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function process($jsLayout)
    {
        $cities = array();

        if ($this->checkoutSession->getQuote()->getShippingAddress()->getCityNovaposhtaRef()) {
            $ref = $this->checkoutSession->getQuote()->getShippingAddress()->getCityNovaposhtaRef();
            $city = $this->cityRepository->getByRef($ref);

            if (!empty($city->getData('ref'))) {
                $cities[] = [
                    'value' => $city->getData('ref'),
                    'label' => $city->getData('name_' . $this->lang),
                ];
            }
        } else {
            $cities[] = [
                'value' => '',
                'label' => '- ' . __('select city'),
            ];
        }

        if (!isset($jsLayout['components']['checkoutProvider']['dictionaries']['city'])) {
            $jsLayout['components']['checkoutProvider']['dictionaries']['city'] = $cities;
        }

        // быстрый набор городов
        $cityFastRefsConfig = $this->scopeConfig->getValue(
                'carriers/novaposhta/city_fast',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if (!empty($cityFastRefsConfig)) {
            $citiesFast = array();
            $cityFastRefsIds = explode(',', $cityFastRefsConfig);
            $searchCriteria = $this->searchCriteriaBuilder->addFilter('ref', $cityFastRefsIds, 'in')->create();
            $result = $this->cityRepository->getList($searchCriteria);
            foreach ($result->getItems() as $item)
            {
                $citiesFast[] = [
                    'value' => $item->getData('ref'),
                    'label' => $item->getData('name_' . $this->lang),
                ];
            }

            if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['city_novaposhta_ref']['config']['city_fast'])) {
                $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['city_novaposhta_ref']['config']['city_fast'] = $citiesFast;
            }
        }

        return $jsLayout;
    }

}
