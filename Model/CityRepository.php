<?php

namespace Yu\NovaPoshta\Model;

class CityRepository implements \Yu\NovaPoshta\Api\CityRepositoryInterface
{

    /**
     * @var \Yu\NovaPoshta\Model\CityFactory
     */
    private $cityFactory;

    /**
     * @var \Yu\NovaPoshta\Model\ResourceModel\City
     */
    private $cityResourceModel;

    /**
     * @var \Yu\NovaPoshta\Model\ResourceModel\City\CollectionFactory
     */
    private $cityCollectionFactory;

    /**
     * @var \Yu\NovaPoshta\Api\Data\CitySearchResultsInterfaceFactory
     */
    private $citySearchResultFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var sting
     */
    private $lang;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Yu\NovaPoshta\Model\CityFactory $cityFactory
     * @param \Yu\NovaPoshta\Model\ResourceModel\City $cityResourceModel
     * @param \Yu\NovaPoshta\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory
     * @param \Yu\NovaPoshta\Api\Data\CitySearchResultsInterfaceFactory $citySearchResultFactory
     */
    public function __construct(
            \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
            \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor,
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
            \Yu\NovaPoshta\Model\CityFactory $cityFactory,
            \Yu\NovaPoshta\Model\ResourceModel\City $cityResourceModel,
            \Yu\NovaPoshta\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory,
            \Yu\NovaPoshta\Api\Data\CitySearchResultsInterfaceFactory $citySearchResultFactory
    )
    {

        $this->cityFactory = $cityFactory;
        $this->cityResourceModel = $cityResourceModel;
        $this->cityCollectionFactory = $cityCollectionFactory;
        $this->citySearchResultFactory = $citySearchResultFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
        $this->scopeConfig = $scopeConfig;
        $this->lang = $scopeConfig->getValue(
                'carriers/novaposhta/lang',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getById($cityId)
    {
        $city = $this->cityFactory->create();
        $this->cityResourceModel->load($city, $cityId);
        return $city;
    }

    /**
     * {@inheritdoc}
     */
    public function getByRef($ref)
    {
        $city = $this->cityFactory->create();
        $this->cityResourceModel->load($city, $ref, 'ref');
        return $city;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->cityCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResult = $this->citySearchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * {@inheritdoc}
     */
    public function getJsonByCityName(string $name = null)
    {
        $data = array();

        if (!empty($name) && mb_strlen($name) > 1) {
            $collection = $this->cityCollectionFactory->create();
            $collection->addFieldToFilter(
                    ['name_ru', 'name_ua'],
                    [
                        ['like' => $name . '%'],
                        ['like' => $name . '%']
                    ]
            );
            foreach ($collection->getItems() as $item)
            {
                $data[] = [
                    'id'   => $item->getData('ref'),
                    'text' => $item->getData('name_' . $this->lang),
                ];
            }
        }

        return json_encode($data);
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Yu\NovaPoshta\Api\Data\CityInterface $city)
    {
        return $this->cityResourceModel->save($city);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\Yu\NovaPoshta\Api\Data\CityInterface $city)
    {
        return $this->cityResourceModel->delete($city);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($cityId)
    {
        $city = $this->getById($cityId);
        if(!empty($city->getId())) {
            return $this->delete($city);
        }
        return false;
    }

}
