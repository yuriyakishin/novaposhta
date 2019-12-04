<?php

namespace Yu\NovaPoshta\Api\Data;

interface CityInterface
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
     * Get City Identifier
     *
     * @return string|null
     */
    public function getRef();
    
    /**
     * Get City Name UA
     *
     * @return string|null
     */
    public function getNameUa();
    
    /**
     * Get City Name RU
     *
     * @return string|null
     */
    public function getNameRu();
    
    /**
     * Get area
     *
     * @return string|null
     */
    public function getArea();
    
    /**
     * Get Type UA
     *
     * @return string|null
     */
    public function getTypeUa();
    
    /**
     * Get Type RU
     *
     * @return string|null
     */
    public function getTypeRu();
    
    /**
     * Set ID
     *
     * @param int $id
     * @return CityInterface
     */
    public function setId($id);
    
    /**
     * Set identifier
     *
     * @param string $identifier
     * @return CityInterface
     */
    public function setIdentifier($identifier);
    
    /**
     * Set City Identifier
     *
     * @param string $ref
     * @return CityInterface
     */
    public function setRef($ref);
    
    /**
     * Set City Name UA
     *
     * @param string $nameUa
     * @return CityInterface
     */
    public function setNameUa($nameUa);
    
    /**
     * Set City Name RU
     *
     * @param string $nameRu
     * @return CityInterface
     */
    public function setNameRu($nameRu);
    
    /**
     * Set area
     *
     * @param string $area
     * @return CityInterface
     */
    public function setArea($area);
    
    /**
     * Set Type UA
     *
     * @param string $typeUa
     * @return CityInterface
     */
    public function setTypeUa($typeUa);
    
    /**
     * Set Type RU
     *
     * @param string $typeRu
     * @return CityInterface
     */
    public function setTypeRu($typeRu);
}
