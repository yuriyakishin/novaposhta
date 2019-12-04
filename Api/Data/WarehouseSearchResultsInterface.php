<?php
namespace Yu\NovaPoshta\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface WarehouseSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get warehouses list.
     *
     * @return \Yu\NovaPoshta\Api\Data\WarehouseInterface[]
     */
    public function getItems();

    /**
     * Set warehouses list.
     *
     * @param \Yu\NovaPoshta\Api\Data\WarehouseInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}