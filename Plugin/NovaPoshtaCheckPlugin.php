<?php

namespace Yu\NovaPoshta\Plugin;


use Magento\Framework\App\RequestInterface;

class NovaPoshtaCheckPlugin
{
    private $request;

    /**
     * @var \Magento\Quote\Api\Data\AddressInterface
     */
    private $address;

    /**
     * NovaPoshtaCheckPlugin constructor.
     * @param \Magento\Quote\Api\Data\AddressInterface $address
     */
    public function __construct(
        \Magento\Framework\Webapi\Rest\Request $request,
        \Magento\Quote\Api\Data\AddressInterface $address
    )
    {
        $this->request = $request;
        $this->address = $address;
    }

    public function afterEstimateByExtendedAddress(
        \Magento\Quote\Api\ShipmentEstimationInterface $subject,
        $result,
        $cartId,
        $addressId)
    {
        $novaposhtaCheck = 0;
        $methods = [];
        /** @var \Magento\Quote\Api\Data\ShippingMethodInterface $item */
        $params = $this->request->getRequestData();
        if (isset($params['address']['custom_attributes'])) {
            foreach ($params['address']['custom_attributes'] as $param) {
                if ($param['attribute_code'] == 'novaposhta_check' && !empty($param['value'])) {
                    $novaposhtaCheck = 1;
                }
            }
        }

        foreach ($result as $item) {
            if ($item->getCarrierCode() == 'novaposhta' && $novaposhtaCheck == 1) {
                $methods[] = $item;
            }

            if ($item->getCarrierCode() !== 'novaposhta' && $novaposhtaCheck == 0) {
                $methods[] = $item;
            }
        }

        return $methods;
    }
}
