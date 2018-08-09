<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\Directory\Plugin\Magento\Quote\Model;

class ShippingAddressManagement
{

    /**
     * @var \SR\Directory\Helper\Data
     */
    protected $helper;

    /**
     * ShippingAddressManagement constructor.
     * @param \SR\Directory\Helper\Data $helper
     */
    public function __construct(
        \SR\Directory\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Quote\Model\ShippingAddressManagement $subject
     * @param $cartId
     * @param \Magento\Quote\Api\Data\AddressInterface $address
     */
    public function beforeAssign(
        \Magento\Quote\Model\ShippingAddressManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\AddressInterface $address
    ) {

        $extAttributes = $address->getExtensionAttributes();

        if (!empty($extAttributes)) {
            $this->helper->transportFieldsFromExtensionAttributesToObject(
                $extAttributes,
                $address,
                'extra_checkout_shipping_address_fields'
            );
        }

    }
}