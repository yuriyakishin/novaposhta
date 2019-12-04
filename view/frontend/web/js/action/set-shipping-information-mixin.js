define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();

            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }

            shippingAddress['extension_attributes']['city_novaposhta_ref'] = $('[name="city_novaposhta_ref"]').val();
            shippingAddress['extension_attributes']['warehouse_novaposhta_id'] = $('[name="warehouse_novaposhta_id"]').val();

            return originalAction();
        });
    };
});