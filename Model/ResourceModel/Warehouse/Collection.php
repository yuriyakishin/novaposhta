<?php

namespace Yu\NovaPoshta\Model\ResourceModel\Warehouse;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    protected function _construct(): void
    {
        $this->_init(
            \Yu\NovaPoshta\Model\Warehouse::class,
            \Yu\NovaPoshta\Model\ResourceModel\Warehouse::class
        );
    }

}
