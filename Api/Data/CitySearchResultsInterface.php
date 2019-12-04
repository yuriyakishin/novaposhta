<?php

namespace Yu\NovaPoshta\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface CitySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get cities list.
     *
     * @return \Yu\NovaPoshta\Api\Data\CityInterface[]
     */
    public function getItems();

    /**
     * Set cities list.
     *
     * @param \Yu\NovaPoshta\Api\Data\CityInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
