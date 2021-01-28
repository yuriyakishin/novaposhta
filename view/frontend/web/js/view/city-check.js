define([
    'jquery',
    'Magento_Ui/js/form/element/single-checkbox'
], function ($, Checkbox) {
    'use strict';

    return Checkbox.extend({
        onCheckedChanged: function (newChecked) {
            this._super(newChecked);
            if(newChecked) {
                $('[name="shippingAddress.city_novaposhta_ref"]').show();
                $('[name="shippingAddress.city"]').hide();
            } else {
                $('[name="shippingAddress.city_novaposhta_ref"]').hide();
                $('[name="shippingAddress.city"]').show();
            }
        }
    });
});
