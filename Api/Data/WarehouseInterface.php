<?php

namespace Yu\NovaPoshta\Api\Data;

interface WarehouseInterface
{
    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();
    
    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier();
    
    /**
     * Get Warehouse Identifier
     *
     * @return string
     */
    public function getRef();
    
    /**
     * Get City Identifier
     *
     * @return string|null
     */
    public function getCityRef();
    
    /**
     * Get Warehouse Name UA
     *
     * @return string|null
     */
    public function getNameUa();
    
    /**
     * Get Warehouse Name RU
     *
     * @return string|null
     */
    public function getNameRu();
    
    /**
     * Get Warehouse number
     *
     * @return int|null
     */
    public function getNumber();
    
    /**
     * Set ID
     *
     * @param int $id
     * @return WarehouseInterface
     */
    public function setId($id);
    
    /**
     * Set identifier
     *
     * @param string $identifier
     * @return WarehouseInterface
     */
    public function setIdentifier($identifier);
    
    /**
     * Set Warehouse Identifier
     *
     * @param string $ref
     * @return WarehouseInterface
     */
    public function setRef($ref);
    
    /**
     * Set City Identifier
     *
     * @param string $cityRef
     * @return WarehouseInterface
     */
    public function setCityRef($cityRef);
    
    /**
     * Set Warehouse Name UA
     *
     * @param string $nameUa
     * @return WarehouseInterface
     */
    public function setNameUa($nameUa);
    
    /**
     * Set Warehouse Name RU
     *
     * @param string $nameRu
     * @return WarehouseInterface
     */
    public function setNameRu($nameRu);
    
    /**
     * Set Warehouse number
     * 
     * @param int $number
     * @return WarehouseInterface
     */
    public function setNumber($number);
}
