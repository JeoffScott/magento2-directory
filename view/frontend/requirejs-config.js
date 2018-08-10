var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'SR_Directory/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/action/create-shipping-address': {
                'SR_Directory/js/action/create-shipping-address-mixin': true
            }
        }
    }
};