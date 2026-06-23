define(['jquery'], function ($) {
    'use strict';

    return function (validation) {
        $.validator.addMethod(
            'validate-json',
            function (value, element) {
                let parsedValue;

                try {
                    parsedValue = JSON.parse(value);
                } catch (e) {
                    return false;
                }

                if (!parsedValue || typeof parsedValue !== 'object' || Array.isArray(parsedValue)) {
                    return false;
                }

                return true;
            },
            $.mage.__('Enter a valid JSON object.')
        );

        return validation;
    };
});
