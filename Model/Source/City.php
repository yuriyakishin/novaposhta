<?php

namespace Yu\NovaPoshta\Model\Source;

class City implements \Magento\Shipping\Model\Carrier\Source\GenericInterface
{
    /**
     * @var \Yu\NovaPoshta\Model\ResourceModel\City\CollectionFactory
     */
    private $cityCollectionFactory;
    
    /**
     * @param \Yu\NovaPoshta\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory
     */
    public function __construct(
        \Yu\NovaPoshta\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory
    ) {
        $this->cityCollectionFactory = $cityCollectionFactory;
    }
    
    /**
     * Returns array to be used in multiselect on back-end
     *
     * @return array
     */
    public function toOptionArray()
    {
        $cityCollection = $this->cityCollectionFactory->create();
        
        $options = [];
        
        foreach($cityCollection as $city)
        {
            $options[] = [
                'value' => $city->getData('ref'),
                'label' => $city->getData('name_ru') . ', ' . $city->getData('type_ru'),
            ];
        }

        return $options;
    }
}
