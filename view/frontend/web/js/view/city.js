define([
    'jquery',
    'Magento_Ui/js/form/element/select',
    'mage/translate',
    'Yu_NovaPoshta/js/lib/select2/select2'
], function ($, Select) {
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
            $(element).select2({
                sorter: function (data) {
                    if (data.length < 100) {
                        return data.sort(function (a, b) {
                            if (a.text.length > b.text.length) {
                                return 1;
                            } else if (a.text.length < b.text.length) {
                                return -1;
                            } else {
                                return 0;
                            }
                        });
                    }
                    return data;
                },
                placeholder: $.mage.__('select city'),
                width: '100%',
                selectOnBlur: true
            });
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
