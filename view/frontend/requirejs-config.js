var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-billing-address': {
                'SR_Directory/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/set-shipping-information': {
                'SR_Directory/js/action/set-shipping-information-mixin': true
            },
            // 'Magento_Checkout/js/action/create-shipping-address': {
            //     'SR_Directory/js/action/create-shipping-address-mixin': true
            // },
            'Magento_Customer/js/model/customer/address': {
                'SR_Directory/js/model/customer/address-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'SR_Directory/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/create-billing-address': {
                'SR_Directory/js/action/set-billing-address-mixin': true
            }
        }
    },
    map: {
        '*': {
            'Magento_Checkout/template/billing-address/details.html':
                'SR_Directory/template/billing-address/details.html',
            'Magento_Checkout/template/shipping-information/address-renderer/default.html':
                'SR_Directory/template/shipping-information/address-renderer/default.html',
            'Magento_Checkout/template/shipping-address/address-renderer/default.html':
                'SR_Directory/template/shipping-address/address-renderer/default.html',
        },

    }
};