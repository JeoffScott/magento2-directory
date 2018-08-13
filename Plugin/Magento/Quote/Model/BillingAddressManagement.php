<?php


namespace SR\Directory\Plugin\Magento\Quote\Model;

class BillingAddressManagement
{

    /**
     * @var \SR\Directory\Helper\Data
     */
    protected $helper;

    /**
     * BillingAddressManagement constructor.
     * @param \SR\Directory\Helper\Data $helper
     */
    public function __construct(
        \SR\Directory\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Quote\Model\BillingAddressManagement $subject
     * @param $cartId
     * @param \Magento\Quote\Api\Data\AddressInterface $address
     * @param bool $useForShipping
     */
    public function beforeAssign(
        \Magento\Quote\Model\BillingAddressManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\AddressInterface $address,
        $useForShipping = false
    ) {

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