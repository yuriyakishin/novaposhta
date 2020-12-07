define([
    'jquery',
    'Magento_Checkout/js/model/url-builder',
    'mage/storage',
    'uiRegistry',
    'Magento_Customer/js/model/address-list',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/checkout-data'
], function ($, urlBuilder, storage, registry, addressList, quote, checkoutData) {

    'use strict';

    var cacheKey = 'checkout-data';

    return {

        cityRef: '',
        cityName: '',
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
            } else if (addressList().length > 0) {

                var cityName = quote.shippingAddress().city;

                if (cityName != this.cityName) {
                    this.cityName = cityName;
                    storage.post(
                            urlBuilder.createUrl('/novaposhta/warehouses-by-city-name', {method: 'post'}),
                            JSON.stringify({cityName: cityName})
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
                }
            };
        }
    };
})
