/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'uiRegistry',
    'SR_Base/js/form/element/autocomplete',
    'jquery',
    'mageUtils'
], function (registry, Autocomplete, $, utils) {
    'use strict';

    //'${ $.parentName }.city:value'
    return Autocomplete.extend({
        defaults: {
            imports: {
                //update:  'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.city:value'
            }
        },

        initLinks : function() {
            this.imports['update'] = utils.getPart(this.parentName, -2) + '.city:value';
            return this._super();
        },

        /**
         * @param {String} value
         */
        update: function (city) {

            if (!city) {
                this.options.data = null;
                return;
            }

            this.value('');

            if (this._xhr) {
                this._xhr.abort();
            }

            this._xhr = $.ajax($.extend(true, {
                url: this.options.sourceUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    "city": city
                },
                success: $.proxy(function (items) {
                    this.options.data = items;
                }, this)
            }, this.options.ajaxOptions || {}));
        },

        initConfig: function () {
            this._super();
        }
    });
});

