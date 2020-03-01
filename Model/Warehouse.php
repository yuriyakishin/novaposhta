<?php

namespace Yu\NovaPoshta\Model;

use Magento\Framework\Model\AbstractModel;

class Warehouse extends AbstractModel
{

    protected function _construct()
    {
        $this->_init(\Yu\NovaPoshta\Model\ResourceModel\Warehouse::class);
    }

}
