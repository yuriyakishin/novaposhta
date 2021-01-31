define([
    'jquery',
    'Magento_Ui/js/form/element/textarea',
    'Magento_Checkout/js/model/quote',
    'mage/translate'
], function ($, Input, quote, $t) {
    'use strict';

    return Input.extend({

        defaults: {
            address: '',
            placeholder: $t('Enter address'),
            exports: {
                "address": "${ $.parentName }.shipping-address-fieldset.street.0:value"
            }
        },

        initialize: function () {
            this._super();
            this.address('');
            return this;
        },

        initObservable: function () {
            this._super();
            this.observe('address');
            return this;
        },

        onUpdate: function () {
            this._super();
            this.address(this.getPreview());
        },

        selectedMethodCode: function () {
            var method = quote.shippingMethod();
            var selectedMethodCode = method != null ? method.method_code : false;
            return selectedMethodCode;
        }
    });
});
