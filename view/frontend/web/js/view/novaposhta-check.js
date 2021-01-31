define([
    'jquery',
    'underscore',
    'Magento_Ui/js/form/element/single-checkbox',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/shipping-service'
], function ($, _, Checkbox, quote, shippingService) {
    'use strict';

    return Checkbox.extend({

        defaults: {
            shippingRates: shippingService.getShippingRates(),
            cityDefault: true,
            cityNovaposhta: false,
            streetDefault: true,
            street: '',
            exports: {
                "cityDefault": "${ $.parentName }.city:visible",
                "cityNovaposhta": "${ $.parentName }.city_novaposhta_ref:visible",
                "streetDefault": "${ $.parentName }.street:visible",
                "street": "${ $.parentName }.street.0:value"
            }
        },

        initObservable: function () {
            this._super();
            this.observe('cityDefault');
            this.observe('cityNovaposhta');
            this.observe('streetDefault');
            this.observe('street');
            this.observe('shippingRates');
            return this;
        },

        onCheckedChanged: function (newChecked) {
            this._super(newChecked);
            if (newChecked) {
                this.cityDefault(false);
                this.streetDefault(false);
                this.cityNovaposhta(true);
                this.street('-');
                $(".street").hide();
            } else {
                this.cityDefault(true);
                this.streetDefault(true);
                this.cityNovaposhta(false);
                $(".street").show();
            }
        }
    });
});
