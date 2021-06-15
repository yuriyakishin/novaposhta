<?php

namespace Yu\NovaPoshta\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class City extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('novaposhta_city', 'city_id');
    }

    /**
     *
     * @param string $name
     * @return string
     */
    public function getRefByName($name)
    {
        $select = $this->getConnection()->select()
                ->from(['city' => $this->getMainTable()], 'ref')
                ->where('city.name_ru=? OR city.name_ua=?', $name)
                ->limit(1);
        $row = $this->getConnection()->fetchRow($select);
        if (empty($row) || empty($row['ref'])) {
            return '';
        }
        return $row['ref'];
    }
}
