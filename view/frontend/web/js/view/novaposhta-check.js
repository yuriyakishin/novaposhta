define([
    'jquery',
    'underscore',
    'Magento_Ui/js/form/element/single-checkbox',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/shipping-rate-registry',
    'uiRegistry'
], function ($, _, Checkbox, quote, rateRegistry, uiRegistry) {
    'use strict';

    return Checkbox.extend({

        defaults: {
            cityDefault: true,
            cityNovaposhta: false,
            streetDefault: true,
            street: '',
            value: 0,
            valueMap: {false: '', true: 'Нова Пошта'},
            exports: {
                "cityDefault": "${ $.parentName }.city:visible",
                "cityNovaposhta": "${ $.parentName }.city_novaposhta_ref:visible",
                "streetDefault": "${ $.parentName }.street:visible",
                "street": "${ $.parentName }.street.0:value"
            }
        },

        initialize: function () {
            this._super();
            return this;
        },

        initObservable: function () {
            this._super();
            this.observe('cityDefault');
            this.observe('cityNovaposhta');
            this.observe('streetDefault');
            this.observe('street');
            this.observe('value');
            return this;
        },

        onExtendedValueChanged: function (newExportedValue) {
            this._super(newExportedValue);
            var check = 0;
            if (this.value() !== '') {
                this.cityDefault(false);
                this.streetDefault(false);
                this.cityNovaposhta(true);
                this.street('-');
                $(".street").hide();
                check = 1;
            } else {
                this.cityDefault(true);
                this.streetDefault(true);
                this.cityNovaposhta(false);
                $(".street").show();
            }

            var address = quote.shippingAddress();
            rateRegistry.set(address.getKey(), null);
            rateRegistry.set(address.getCacheKey(), null);
            if (address.customAttributes == undefined) {
                address.customAttributes = {}
            }
            address.customAttributes = _.extend(address.customAttributes, [{
                "attribute_code": "novaposhta_check",
                "value": this.value()
            }]);
            uiRegistry.set('shippingAddress', address);
            quote.shippingAddress(address);
        }
    });
});
