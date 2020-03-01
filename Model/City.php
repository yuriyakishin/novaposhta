<?php

namespace Yu\NovaPoshta\Model;

use Magento\Framework\Model\AbstractModel;
use Yu\NovaPoshta\Api\Data\CityInterface;

class City extends AbstractModel implements CityInterface
{

    protected function _construct()
    {
        $this->_init(\Yu\NovaPoshta\Model\ResourceModel\City::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getArea()
    {
        return $this->getData('area');
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->getData('city_id');
    }

    /**
     * {@inheritdoc}
     */
    public function getNameRu()
    {
        return $this->getData('name_ru');
    }

    /**
     * {@inheritdoc}
     */
    public function getNameUa()
    {
        return $this->getData('name_ua');
    }

    /**
     * {@inheritdoc}
     */
    public function getRef()
    {
        return $this->getData('ref');
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeRu()
    {
        return $this->getData('type_ru');
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeUa()
    {
        return $this->getData('type_ua');
    }

    /**
     * {@inheritdoc}
     */
    public function setArea($area)
    {
        return $this->setData('area', $area);
    }

    /**
     * {@inheritdoc}
     */
    public function setIdentifier($identifier)
    {
        return $this->setData('city_id', $identifier);
    }

    /**
     * {@inheritdoc}
     */
    public function setNameRu($nameRu)
    {
        return $this->setData('name_ru', $nameRu);
    }

    /**
     * {@inheritdoc}
     */
    public function setNameUa($nameUa)
    {
        return $this->setData('name_ua', $nameUa);
    }

    /**
     * {@inheritdoc}
     */
    public function setRef($ref)
    {
        return $this->setData('ref', $ref);
    }

    /**
     * {@inheritdoc}
     */
    public function setTypeRu($typeRu)
    {
        return $this->setData('type_ru', $typeRu);
    }

    /**
     * {@inheritdoc}
     */
    public function setTypeUa($typeUa)
    {
        return $this->setData('type_ua', $typeUa);
    }

}