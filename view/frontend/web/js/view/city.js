define([
    'jquery',
    'Magento_Ui/js/form/element/select',
    'mage/url',
    'mage/translate',
    'Yu_NovaPoshta/js/lib/select2/select2',
    'Yu_NovaPoshta/js/lib/select2/i18n/ru',
    'Yu_NovaPoshta/js/lib/select2/i18n/uk'
], function ($, Select, url) {
    'use strict';
    return Select.extend({

        defaults: {
            cityName: '',
            exports: {
                cityName: '${ $.parentName }.city:value'
            }
        },

        initialize: function () {
            this._super();
            this.cityName(this.getPreview());
            return this;
        },

        initObservable: function () {
            this._super();
            this.observe('cityName');
            return this;
        },

        select2: function (element) {
            var lang = "ru";
            if ($('html').attr('lang') == "uk") {
                lang = "uk";
            }
            ;
            $(element).select2({
                placeholder: $.mage.__('select city'),
                width: 'element',
                minimumInputLength: 2,
                language: lang,
                ajax: {
                    url: url.build('rest/V1/novaposhta/city'),
                    type: "POST",
                    dataType: 'json',
                    contentType: "application/json",
                    delay: 1000,
                    data: function (params) {
                        var query = JSON.stringify({
                            name: params.term
                        })
                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: JSON.parse(data)
                        };
                    }
                }
            });
        },

        getPreview: function () {
            return $('[name="' + this.inputName + '"] option:selected').text();
        },

        getCityName: function () {
            return this.cityName();
        },

        setDifferedFromDefault: function () {
            this._super();
            this.cityName(this.getPreview());
        }

    });
});