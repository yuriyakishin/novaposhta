<?php

namespace Yu\NovaPoshta\Plugin;

use Magento\Framework\App\RequestInterface;

class NovaPoshtaCheckPlugin
{
    /**
     * @var \Magento\Framework\Webapi\Rest\Request
     */
    private $request;

    /**
     * NovaPoshtaCheckPlugin constructor.
     *
     * @param \Magento\Framework\Webapi\Rest\Request $request
     */
    public function __construct(
        \Magento\Framework\Webapi\Rest\Request $request
    ) {
        $this->request = $request;
    }

    public function afterEstimateByExtendedAddress(
        \Magento\Quote\Api\ShipmentEstimationInterface $subject,
        $result,
        $cartId,
        $addressId
    ) {
        $novaposhtaCheck = false;
        $methods = [];
        /** @var \Magento\Quote\Api\Data\ShippingMethodInterface $item */
        $params = $this->request->getRequestData();
        if (isset($params['address']['custom_attributes'])) {
            foreach ($params['address']['custom_attributes'] as $param) {
                if ($param['attribute_code'] === 'novaposhta_check' && !empty($param['value'])) {
                    $novaposhtaCheck = true;
                }
            }
        }

        foreach ($result as $item) {
            if ($novaposhtaCheck === true && $item->getCarrierCode() === 'novaposhta') {
                $methods[] = $item;
            }

            if ($novaposhtaCheck === false && $item->getCarrierCode() !== 'novaposhta') {
                $methods[] = $item;
            }
        }

        return $methods;
    }
}
