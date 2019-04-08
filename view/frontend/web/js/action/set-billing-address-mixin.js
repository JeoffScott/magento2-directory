define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper,quote) {
    'use strict';

    return function (setBillingAddressAction) {
        return wrapper.wrap(setBillingAddressAction, function (originalAction, messageContainer) {

            var billingAddress = quote.billingAddress();

            if(billingAddress != undefined) {

                if (billingAddress['extension_attributes'] === undefined) {
                    billingAddress['extension_attributes'] = {};
                }

                if (billingAddress.customAttributes != undefined) {
                    $.each(billingAddress.customAttributes, function () {

                        var self = this;

                        if (self['attribute_code'] != undefined) {
                        billingAddress['extension_attributes'][self['attribute_code']] = self['value'];
                        }
                    });

                    //additional checking and adding data, popups when customer logged in and came from the shipping page
                    if ($.isEmptyObject(billingAddress['extension_attributes'])) {

                        $.each(billingAddress.customAttributes, function (key, value) {

                            if($.isPlainObject(value)){
                                value = value['value'];
                            }

                            billingAddress['extension_attributes'][key] = value;
                        });
                    }

                }

            }

            return originalAction(messageContainer);
        });
    };
});