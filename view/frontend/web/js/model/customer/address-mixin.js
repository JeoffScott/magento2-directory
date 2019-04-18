define([
    'jquery',
    'mage/utils/wrapper',
    'mage/translate'
], function($, wrapper) {
    'use strict';

    return function (addressModel) {
        return wrapper.wrap(addressModel, function(originalAction) {
            var address = originalAction();

            if(address.customAttributes !== undefined) {
                var customAt = [];
                $.each(address.customAttributes, function(key, value){
                    customAt.push(address.customAttributes[key])
                });
                address.customAttributes = customAt;
            }

            return address;
        });
    };
});