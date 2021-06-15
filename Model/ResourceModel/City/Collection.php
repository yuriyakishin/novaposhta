<?php

namespace Yu\NovaPoshta\Model\ResourceModel\City;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(
            \Yu\NovaPoshta\Model\City::class,
            \Yu\NovaPoshta\Model\ResourceModel\City::class
        );
    }
}
