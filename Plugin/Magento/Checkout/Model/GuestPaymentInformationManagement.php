<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\Directory\Plugin\Magento\Checkout\Model;

class GuestPaymentInformationManagement
{

    /**
     * @var \SR\Directory\Helper\Data
     */
    protected $helper;

    /**
     * PaymentInformationManagement constructor.
     * @param \SR\Directory\Helper\Data $helper
     */
    public function __construct(
        \SR\Directory\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Checkout\Model\GuestPaymentInformationManagement $subject
     * @param $cartId
     * @param \Magento\Quote\Api\Data\PaymentInterface $paymentMethod
     * @param \Magento\Quote\Api\Data\AddressInterface $address
     */
    public function beforeSavePaymentInformation(
        \Magento\Checkout\Model\GuestPaymentInformationManagement $subject,
        $cartId,
        $email,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\AddressInterface $address
    ) {
        if($address) {
            $extAttributes = $address->getExtensionAttributes();
            if (!empty($extAttributes)) {
                $this->helper->transportFieldsFromExtensionAttributesToObject(
                    $extAttributes,
                    $address,
                    'extra_checkout_billing_address_fields'
                );
            }
        }
    }
}