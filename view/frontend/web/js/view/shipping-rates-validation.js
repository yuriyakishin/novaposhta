define(
        [
            'uiComponent',
            'Magento_Checkout/js/model/shipping-rates-validator',
            'Magento_Checkout/js/model/shipping-rates-validation-rules',
            'Yu_NovaPoshta/js/model/shipping-rates-validator',
            'Yu_NovaPoshta/js/model/shipping-rates-validation-rules'
        ],
        function (
                Component,
                defaultShippingRatesValidator,
                defaultShippingRatesValidationRules,
                shippingRatesValidator,
                shippingRatesValidationRules
                ) {
            'use strict';

            defaultShippingRatesValidator.registerValidator('novaposhta', shippingRatesValidator);
            defaultShippingRatesValidationRules.registerRules('novaposhta', shippingRatesValidationRules);

            return Component;
        }
);