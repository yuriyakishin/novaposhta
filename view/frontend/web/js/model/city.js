define([
    'jquery',
    'Magento_Checkout/js/model/url-builder',
    'mage/storage',
    'uiRegistry'
], function ($, urlBuilder, storage, registry) {

    'use strict';

    //console.log(registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.region'));


    return {

        cityRef: '',
        cities: [],

        getCityRef: function () {
            var ref = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.city_novaposhta_ref').value();
            return ref;
        },

        loadCityWarehouses: function (warehouse) {

            var cityRef = this.getCityRef();

            if (cityRef !== this.cityRef && cityRef !== '' && cityRef !== undefined) {

                this.cityRef = cityRef;

                storage.post(
                        urlBuilder.createUrl('/novaposhta/warehouses', {method: 'post'}),
                        JSON.stringify({cityRef: cityRef})
                        ).done(
                        function (data) {
                            this.cities = $.parseJSON(data);
                            warehouse.setWarehouses(this.cities);
                        }
                ).fail(
                        function () {
                            alert("Ошибка загрузки данных.");
                        }
                );
            };
        }
    };
})
