define([
    'jquery',
    'Magento_Ui/js/form/element/select',
    'Yu_NovaPoshta/js/lib/chosen.jquery'
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
        
        chosen: function(element) {
            $(element).chosen({
                disable_search_threshold: 10,
                no_results_text: "Ничего не найдено!",
                width: "100%"
            });
        },
        
        getCityName: function() {            
            return this.cityName();
        },
        
        setDifferedFromDefault: function () {
            this._super();
            this.cityName(this.getPreview());
        }

    });
});
