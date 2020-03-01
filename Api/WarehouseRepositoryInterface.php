<?php
namespace Yu\NovaPoshta\Api;

use \Yu\NovaPoshta\Api\Data\WarehouseInterface;

interface WarehouseRepositoryInterface
{
    /**
     * Save warehouse.
     *
     * @param \Yu\NovaPoshta\Api\Data\WarehouseInterface $warehouse
     * @return \Yu\NovaPoshta\Api\Data\WarehouseInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(WarehouseInterface $warehouse);

    /**
     * Retrieve warehouse.
     *
     * @param int $warehouseId
     * @return \Yu\NovaPoshta\Api\Data\WarehouseInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($warehouseId);
    
    /**
     * Retrieve warehouse.
     *
     * @param string $cityRef
     * @return \Yu\NovaPoshta\Api\Data\WarehouseSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getListByCityRef($cityRef);
    
    /**
     * Retrieve warehouse.
     *
     * @param string $cityRef
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getJsonByCityRef($cityRef);
    
    /**
     * Retrieve warehouse.
     *
     * @param string $cityName
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getJsonByCityName($cityName);

    /**
     * Retrieve warehouses matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Yu\NovaPoshta\Api\Data\WarehouseSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete warehouse.
     *
     * @param \Yu\NovaPoshta\Api\Data\WarehouseInterface $warehouse
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(WarehouseInterface $warehouse);

    /**
     * Delete warehouse by ID.
     *
     * @param int $warehouseId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($warehouseId);
}
