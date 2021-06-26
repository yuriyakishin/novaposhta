<?php

namespace Yu\NovaPoshta\Plugin;

class ShippingInformationManagementPlugin
{
    /**
     * Quote repository.
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;

    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }
    
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
        $shippingAddress->setWarehouseNovaposhtaAddress($extensionAttributes->getWarehouseNovaposhtaAddress());
    }

    /**
     *
     * @param \Magento\Checkout\Model\ShippingInformationManagement   $subject
     * @param \Magento\Checkout\Api\Data\PaymentDetailsInterface      $result
     * @param int                                                     $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     *
     * @return \Magento\Checkout\Api\Data\PaymentDetailsInterface
     */
    public function afterSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $result,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        $shippingAddress = $quote->getShippingAddress();

        if ($addressInformation->getShippingMethodCode() === 'novaposhta_to_warehouse') {
            $shippingAddress->setStreet($shippingAddress->getWarehouseNovaposhtaAddress());
            $shippingAddress->save();
        }
        return $result;
    }
}
