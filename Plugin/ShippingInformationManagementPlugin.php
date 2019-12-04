<?php

namespace Yu\NovaPoshta\Plugin;

class ShippingInformationManagementPlugin
{
    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param int $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $shippingAddress = $addressInformation->getShippingAddress();
        $extensionAttributes = $shippingAddress->getExtensionAttributes();
        $shippingAddress->setCityNovaposhtaRef($extensionAttributes->getCityNovaposhtaRef());
        $shippingAddress->setWarehouseNovaposhtaId($extensionAttributes->getWarehouseNovaposhtaId());
    }
}
