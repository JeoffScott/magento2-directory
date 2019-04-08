<?php


namespace SR\Directory\Plugin\Magento\Checkout\Model;

/**
 * Class GuestPaymentInformationManagement
 * @package SR\Directory\Plugin\Magento\Checkout\Model
 */
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
     * @param $email
     * @param \Magento\Quote\Api\Data\PaymentInterface $paymentMethod
     * @param \Magento\Quote\Api\Data\AddressInterface $billingAddress
     */
    public function beforeSavePaymentInformation(
        \Magento\Checkout\Model\GuestPaymentInformationManagement $subject,
        $cartId,
        $email,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\AddressInterface $billingAddress
    ) {
        if ($billingAddress) {
            $extAttributes = $billingAddress->getExtensionAttributes();
            if (!empty($extAttributes)) {
                $this->helper->transportFieldsFromExtensionAttributesToObject(
                    $extAttributes,
                    $billingAddress,
                    'extra_checkout_billing_address_fields'
                );
            }
        }
    }
}