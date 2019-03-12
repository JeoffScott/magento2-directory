/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'uiRegistry',
    'Magento_Ui/js/form/element/post-code',
    'jquery',
    'mage/translate',
    'ko'
], function (registry, Postcode, $, ko) {
    'use strict';

    return Postcode.extend({
        defaults: {
            additionalClasses: 'postcode-field'
        },

        initObservable: function () {
            this._super();
            this.observe('isLoading');

            return this;
        },

        /**
         * Parse html and return response text
         * @param html
         */
        getResponseText: function(html) {
            var wrapper = $('<div/>');
            var htmlContent = $.parseHTML(html);
            return $.trim(wrapper.append(htmlContent).text());
        },

        /**
         * @param response
         */
        updateValue: function(response) {
            var responseText = this.getResponseText(response);
            var regex = /(RES)\d{6,}/i;
            var regexErr = /(\D*)$/;

            if(regex.test(responseText)){
                this.value(responseText.substr(responseText.length - 7));
            } else {
                this.error(responseText.match(regexErr)[1]);
            }
        },

        /**
         * Update postcode
         */
        updatePostcode: function () {
            var city = registry.get(this.parentName + '.city').value();
            var street = registry.get(this.parentName + '.street.0').value();
            var house = registry.get(this.parentName + '.house_number').value() || null;
            this.value('');
            this.warn('');

            if(!city && !street) {
                this.warn($.mage.__('Please choose city and street'));
                return;
            }

            if (this._xhr) {
                this._xhr.abort();
            }
            this.isLoading(true);

            this._xhr = $.ajax($.extend(true, {
                url: this.options.sourceUrl,
                type: 'GET',
                data: {
                    "OpenAgent": null,
                    "Location": city,
                    "POB": null,
                    "Street": street,
                    "House": house,
                    "Entrance": null
                },
                success: $.proxy(function (response) {
                    this.updateValue(response.postcode);
                    this.isLoading(false);
                }, this)
            }, this.options.ajaxOptions || {}));
        }
    });
});

