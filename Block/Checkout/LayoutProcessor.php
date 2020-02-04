<?php

namespace Yu\NovaPoshta\Block\Checkout;

/**
 * Класс добавляет список городов в jsLayout
 */
class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{

    /**
     * @var \Yu\NovaPoshta\Model\ResourceModel\City\CollectionFactory
     */
    private $cityCollectionFactory;

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
            \Yu\NovaPoshta\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->cityCollectionFactory = $cityCollectionFactory;
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
        $cityCollection = $this->cityCollectionFactory->create();

        $cities = [];
        $cities[] = [
            'value' => '',
            'label' => '- ' . __('select city'),
        ];

        foreach ($cityCollection as $city)
        {
            $cities[] = [
                'value' => $city->getData('ref'),
                'label' => $city->getData('name_' . $this->lang),
            ];
        }

        if (!isset($jsLayout['components']['checkoutProvider']['dictionaries']['city'])) {
            //$jsLayout['components']['checkoutProvider']['dictionaries']['city'] = $cities;
        }
        return $jsLayout;
    }

}
