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
                        'city_novaposhta_ref': {
                            'required': true
                        }
                    };
                }
            };
        }
);
