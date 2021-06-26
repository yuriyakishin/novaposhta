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
     * @var \Yu\NovaPoshta\Model\Config
     */
    private $config;

    /**
     * @param \Magento\Checkout\Model\Session                    $checkoutSession
     * @param \Magento\Framework\Api\SearchCriteriaBuilder       $searchCriteriaBuilder
     * @param \Yu\NovaPoshta\Api\CityRepositoryInterface         $cityRepository
     * @param \Yu\NovaPoshta\Model\Config                        $config
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Yu\NovaPoshta\Api\CityRepositoryInterface $cityRepository,
        \Yu\NovaPoshta\Model\Config $config
    ) {
        $this->cityRepository = $cityRepository;
        $this->checkoutSession = $checkoutSession;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->config = $config;
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
        $cities = [];

        if ($this->checkoutSession->getQuote()->getShippingAddress()->getCityNovaposhtaRef()) {
            $ref = $this->checkoutSession->getQuote()->getShippingAddress()->getCityNovaposhtaRef();
            $city = $this->cityRepository->getByRef($ref);

            if (!empty($city->getData('ref'))) {
                $cities[] = [
                    'value' => $city->getData('ref'),
                    'label' => $city->getData('name_' . $this->config->getLanguage()),
                ];
            }
        } else {
            $cities[] = [
                'value' => '',
                'label' => '- ' . __(''),
            ];
        }

        if (!isset($jsLayout['components']['checkoutProvider']['dictionaries']['city'])) {
            $jsLayout['components']['checkoutProvider']['dictionaries']['city'] = $cities;
        }

        // быстрый набор городов
        $cityFastRefsConfig = $this->config->getCityFast();

        if (!empty($cityFastRefsConfig)) {
            $citiesFast = [];
            $cityFastRefsIds = explode(',', $cityFastRefsConfig);
            $searchCriteria = $this->searchCriteriaBuilder->addFilter('ref', $cityFastRefsIds, 'in')->create();
            $result = $this->cityRepository->getList($searchCriteria);
            foreach ($result->getItems() as $item) {
                $citiesFast[] = [
                    'value' => $item->getData('ref'),
                    'label' => $item->getData('name_' . $this->config->getLanguage()),
                ];
            }

            if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                      ['shippingAddress']['children']['shipping-address-fieldset']['children']
                      ['city_novaposhta_ref']['config']['city_fast'])) {
                $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                ['shippingAddress']['children']['shipping-address-fieldset']['children']
                ['city_novaposhta_ref']['config']['city_fast'] = $citiesFast;
            }
        }

        return $jsLayout;
    }
}
