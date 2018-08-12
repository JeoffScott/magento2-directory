<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\Directory\Block\Plugin\Magento\Checkout;

class LayoutProcessor
{
    /**
     * @var \Magento\Backend\Block\Template\Context
     */
    protected $_context;

    /**
     * @var \SR\Directory\Model\Config\Source\Cities
     */
    protected $_citySource;

    /**
     * @var \SR\Directory\Model\Config\Source\Streets
     */
    protected $_streetSource;

    /**
     * @var \SR\Directory\Helper\Data
     */
    protected $helper;

    /**
     * LayoutProcessor constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \SR\Directory\Model\Config\Source\Cities $cities
     * @param \SR\Directory\Model\Config\Source\Streets $streets
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \SR\Directory\Model\Config\Source\Cities $cities,
        \SR\Directory\Model\Config\Source\Streets $streets,
        \SR\Directory\Helper\Data $helper
    )
    {
        $this->_context = $context;
        $this->_citySource = $cities;
        $this->_streetSource = $streets;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $subject, array $jsLayout)
    {


        // TODO: implement autocomplete to billing address fields; city and street.

        // prepare city field

        $cityConfig = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['city'];

        $cityConfig = $this->_getCityComponentConfig($cityConfig);


        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['city'] = $cityConfig;


        // prepare street field

        $_streetLines = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['children'];


        $_streetLines = $this->_getStreetLinesComponentConfig($_streetLines);


        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['children'] = $_streetLines;

        $houseNumberAttributeCode = 'house_number';

        $houseNumberField = $this->getFields('shippingAddress.custom_attributes','shipping');

        $jsLayout['components']['checkout']['children']['steps']['children']
        ['shipping-step']['children']['shippingAddress']['children']
        ['shipping-address-fieldset']['children'][$houseNumberAttributeCode] = $houseNumberField[$houseNumberAttributeCode];

        return $jsLayout;
    }

    /**
     * @param string $addressType
     * @return array
     */
    public function getAdditionalFields($addressType = 'shipping')
    {
        return $this->helper->getExtraCheckoutAddressFields('extra_checkout_shipping_address_fields');
    }

    /**
     * @param $scope
     * @param $addressType
     * @return array
     */
    public function getFields($scope, $addressType)
    {
        $fields = [];
        foreach ($this->getAdditionalFields($addressType) as $field) {
            $fields[$field] = $this->getField($field, $scope);
        }
        return $fields;
    }

    /**
     * @param $attributeCode
     * @param $scope
     * @return array
     */
    public function getField($attributeCode,$scope)
    {

        $field = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                // customScope is used to group elements within a single form (e.g. they can be validated separately)
                'customScope' => $scope,
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'tooltip' => [
                    'description' => __('Enter house number separate from street'),
                ],
            ],
            'dataScope' => $scope . '.' . $attributeCode,
            'label' => __('House Number'),
            'provider' => 'checkoutProvider',
            'sortOrder' => 70   ,
            'validation' => [
                'required-entry' => true, 'min_text_len‌​gth' => 1, 'max_text_length' => 10
            ],
            'options' => [],
            'filterBy' => null,
            'customEntry' => null,
            'visible' => true,
        ];

        return $field;
    }

    /**
     * @param $_streetLines
     * @return array
     */
    private function _getStreetLinesComponentConfig($_streetLines)
    {
        $_streetLine1 = array_merge($_streetLines[0], [
            'component' => 'SR_Directory/js/form/element/street',
            'config' => [
                'elementTmpl' => 'SR_Base/form/element/autocomplete',
                'tooltip' => [
                    'description' => __('Start typing to search for your street.'),
                ]
            ],
            'options' => [
                'sourceUrl' => $this->_context->getUrlBuilder()->getUrl('srdirectory/ajax/getstreets'),
                'validateValue' => true
            ],
            'sortOrder' => 60
        ]);

        $_streetLines = [
            0 => $_streetLine1
        ];

        return $_streetLines;
    }

    /**
     * @param $cityConfig
     * @return array
     */
    private function _getCityComponentConfig($cityConfig)
    {
        $_cityConfig = [
            'component' => 'SR_Base/js/form/element/autocomplete',
            'config' => [
                'elementTmpl' => 'SR_Base/form/element/autocomplete',
                'tooltip' => [
                    'description' => __('Start typing to search for your city.'),
                ]
            ],
            'options' => [
                'data' => $this->_citySource->getAllOptions(),
                'validateValue' => true
            ],
            'sortOrder' => 65 // city should come before street. street position is 70.
        ];

        return array_merge($cityConfig, $_cityConfig);
    }

}
