<?php

namespace SR\Directory\Plugin\Checkout;

class ShippingInformationManagement
{

    protected $quoteRepository;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $shippingAddress = $addressInformation->getShippingAddress();
        $extAttributes = $shippingAddress->getExtensionAttributes();
        $shippingAddress->setHouseNumber($extAttributes->getHouseNumber());
        $addressInformation->setShippingAddress($shippingAddress);
//        $addressInformation->setHouseNumber($houseNumber);
        $billingAddress = $addressInformation->getBillingAddress();
        $addressInformation->setBillingAddress($billingAddress->setHouseNumber($billingAddress->getExtensionAttributes()->getHouseNumber()));
    }
}