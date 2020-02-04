<?php

namespace Yu\NovaPoshta\Api;

use Yu\NovaPoshta\Api\Data\CityInterface;

interface CityRepositoryInterface
{
    /**
     * Save city.
     *
     * @param \Yu\NovaPoshta\Api\Data\CityInterface $city
     * @return \Yu\NovaPoshta\Api\Data\CityInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(CityInterface $city);

    /**
     * Retrieve city.
     *
     * @param int $cityId
     * @return \Yu\NovaPoshta\Api\Data\CityInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($cityId);
    
    /**
     * Retrieve city.
     *
     * @param string $ref
     * @return \Yu\NovaPoshta\Api\Data\CityInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByRef($ref);

    /**
     * Retrieve cities matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Yu\NovaPoshta\Api\Data\CitySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
    
    /**
     * Retrieve cities matching name.
     *
     * @param string $name
     * @return string | null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getJsonByCityName(string $name = '');

    /**
     * Delete city.
     *
     * @param \Yu\NovaPoshta\Api\Data\CityInterface $city
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(CityInterface $city);

    /**
     * Delete city by ID.
     *
     * @param int $cityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($cityId);
}
