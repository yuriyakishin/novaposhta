<?php

namespace Yu\NovaPoshta\Model;

use Magento\Framework\Model\AbstractModel;

class City extends AbstractModel
{

    protected function _construct(): void
    {
        $this->_init(\Yu\NovaPoshta\Model\ResourceModel\City::class);
    }
}
