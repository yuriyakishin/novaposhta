<?php

namespace Yu\NovaPoshta\Model;

use Magento\Framework\Model\AbstractModel;
use Yu\NovaPoshta\Api\Data\CityInterface;

class City extends AbstractModel implements CityInterface
{

    protected function _construct(): void
    {
        $this->_init(\Yu\NovaPoshta\Model\ResourceModel\City::class);
    }
    
    public function getArea()
    {
        $this->getData('area');
    }

    public function getIdentifier()
    {
        $this->getData('city_id');
    }

    public function getNameRu()
    {
        $this->getData('name_ru');
    }

    public function getNameUa()
    {
        $this->getData('name_ua');
    }

    public function getRef()
    {
        $this->getData('ref');
    }

    public function getTypeRu()
    {
        $this->getData('type_ru');
    }

    public function getTypeUa()
    {
        $this->getData('type_ua');
    }

    public function setArea($area)
    {
        return $this->setData('area', $area);
    }

    public function setIdentifier($identifier)
    {
        return $this->setData('city_id', $identifier);
    }

    public function setNameRu($nameRu)
    {
        return $this->setData('name_ru', $nameRu);
    }

    public function setNameUa($nameUa)
    {
        return $this->setData('name_ua', $nameUa);
    }

    public function setRef($ref)
    {
        return $this->setData('ref', $ref);
    }

    public function setTypeRu($typeRu)
    {
        return $this->setData('type_ru', $typeRu);
    }

    public function setTypeUa($typeUa)
    {
        return $this->setData('type_ua', $typeUa);
    }

}