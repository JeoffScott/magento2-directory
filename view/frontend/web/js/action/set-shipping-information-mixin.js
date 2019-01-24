define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper,quote) {
    'use strict';

    return function (setShippingInformationAction) {
        return wrapper.wrap(setShippingInformationAction, function (originalAction, messageContainer) {

            var shippingAddress = quote.shippingAddress();

            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }

            if (shippingAddress.customAttributes != undefined) {
                $.each(shippingAddress.customAttributes , function() {

                    var self = this;

                    shippingAddress['customAttributes'][self['attribute_code']] = self['value'];
                    shippingAddress['extension_attributes'][self['attribute_code']] = self['value'];

                });
            }

            return originalAction(messageContainer);
        });
    };
});