<?php

namespace Yu\NovaPoshta\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Yu\NovaPoshta\Api\Data\WarehouseInterface;

class WarehouseRepository implements \Yu\NovaPoshta\Api\WarehouseRepositoryInterface
{
    /**
     * @var \Yu\NovaPoshta\Model\WarehouseFactory
     */
    private $warehouseFactory;

    /**
     * @var \Yu\NovaPoshta\Model\ResourceModel\Warehouse
     */
    private $warehouseResourceModel;

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
     * @var \Yu\NovaPoshta\Model\ResourceModel\City
     */
    private $resourceModelCity;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaBuilder                       $searchCriteriaBuilder
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param \Yu\NovaPoshta\Model\WarehouseFactory                              $warehouseFactory
     * @param \Yu\NovaPoshta\Model\ResourceModel\Warehouse                       $warehouseResourceModel
     * @param \Yu\NovaPoshta\Model\ResourceModel\Warehouse\CollectionFactory     $warehouseCollectionFactory
     * @param \Yu\NovaPoshta\Api\Data\WarehouseSearchResultsInterfaceFactory     $warehouseSearchResultFactory
     * @param \Yu\NovaPoshta\Model\ResourceModel\City                            $resourceModelCity
     */
    public function __construct(
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor,
        \Yu\NovaPoshta\Model\WarehouseFactory $warehouseFactory,
        \Yu\NovaPoshta\Model\ResourceModel\Warehouse $warehouseResourceModel,
        \Yu\NovaPoshta\Model\ResourceModel\Warehouse\CollectionFactory $warehouseCollectionFactory,
        \Yu\NovaPoshta\Api\Data\WarehouseSearchResultsInterfaceFactory $warehouseSearchResultFactory,
        \Yu\NovaPoshta\Model\ResourceModel\City $resourceModelCity,
        \Yu\NovaPoshta\Model\Config $config
    ) {
        $this->warehouseFactory = $warehouseFactory;
        $this->warehouseResourceModel = $warehouseResourceModel;
        $this->warehouseCollectionFactory = $warehouseCollectionFactory;
        $this->warehouseSearchResultFactory = $warehouseSearchResultFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
        $this->resourceModelCity = $resourceModelCity;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($warehouseId)
    {
        $warehouse = $this->warehouseFactory->create();
        $this->warehouseResourceModel->load($warehouse, $warehouseId);
        return $warehouse;
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
        $data = [];
        foreach ($result->getItems() as $item) {
            $data[] = [
                'value' => $item->getData('warehouse_id'),
                'label' => $item->getData('name_' . $this->config->getLanguage()),
            ];
        }

        return json_encode($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getJsonByCityName($cityName)
    {
        $cityRef = $this->resourceModelCity->getRefByName($cityName);
        return $this->getJsonByCityRef($cityRef);
    }

    /**
     * {@inheritdoc}
     */
    public function save(WarehouseInterface $warehouse)
    {
        return $this->warehouseResourceModel->save($warehouse);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(WarehouseInterface $warehouse)
    {
        return $this->warehouseResourceModel->delete($warehouse);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($warehouseId)
    {
        $warehouse = $this->getById($warehouseId);
        return $this->warehouseResourceModel->delete($warehouse);
    }
}
