<?php

namespace Yu\NovaPoshta\Model\Source;

/**
 * Method source
 */
class Method implements \Magento\Shipping\Model\Carrier\Source\GenericInterface
{
    /**
     * Returns array to be used in multiselect on back-end
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'novaposhta_to_warehouse', 'label' => 'До склада'],
            ['value' => 'novaposhta_to_door', 'label' => 'До дверей'],
        ];
    }
}
