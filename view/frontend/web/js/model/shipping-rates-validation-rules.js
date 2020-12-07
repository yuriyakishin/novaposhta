define(
        [],
        function () {
            "use strict";
            return {
                getRules: function () {
                    return {
                        'postcode': {
                            'required': false
                        },
                        'country_id': {
                            'required': false
                        },
                        'region': {
                            'required': false
                        },
                        'region_id': {
                            'required': false
                        },
                        'city_novaposhta_ref': {
                            'required': true
                        },
                        'city': {
                            'required': true
                        }
                    };
                }
            };
        }
);
