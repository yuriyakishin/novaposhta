<?php

namespace Yu\NovaPoshta\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Yu\NovaPoshta\Api\Data\WarehouseInterface;

class WarehouseRepository implements \Yu\NovaPoshta\Api\WarehouseRepositoryInterface
{

    /**
     * @var \Yu\NovaPoshta\Model\ResourceModel\Warehouse\CollectionFactory
     */
    private $warehouseCollectionFactory;

    /**
     * @var \Yu\NovaPoshta\Api\Data\WarehouseSearchResultsInterfaceFactory 
     */
    private $warehouseSearchResultFactory;

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
     * 
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Yu\NovaPoshta\Model\ResourceModel\Warehouse\CollectionFactory $warehouseCollectionFactory
     * @param \Yu\NovaPoshta\Api\Data\WarehouseSearchResultsInterfaceFactory $warehouseSearchResultFactory
     */
    public function __construct(
            \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
            \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor,
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
            \Yu\NovaPoshta\Model\ResourceModel\Warehouse\CollectionFactory $warehouseCollectionFactory,
            \Yu\NovaPoshta\Api\Data\WarehouseSearchResultsInterfaceFactory $warehouseSearchResultFactory
    )
    {
        $this->warehouseCollectionFactory = $warehouseCollectionFactory;
        $this->warehouseSearchResultFactory = $warehouseSearchResultFactory;
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
    public function delete(WarehouseInterface $warehouse)
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($warehouseId)
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function getById($warehouseId)
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->warehouseCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResult = $this->warehouseSearchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * {@inheritdoc}
     */
    public function getListByCityRef($cityRef)
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('city_ref', $cityRef)->create();
        return $this->getList($searchCriteria);
    }

    /**
     * {@inheritdoc}
     */
    public function getJsonByCityRef($cityRef)
    {
        $result = $this->getListByCityRef($cityRef);
        $data = array();
        foreach($result->getItems() as $item)
        {
            $data[] = [
                'value' => $item->getData('warehouse_id'),
                'label' => $item->getData('name_'.$this->lang),
            ];
        }
        return json_encode($data);
    }

    /**
     * {@inheritdoc}
     */
    public function save(WarehouseInterface $warehouse)
    {
        
    }

}
