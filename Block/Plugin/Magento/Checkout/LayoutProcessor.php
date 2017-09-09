<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\Directory\Block\Plugin\Magento\Checkout;

class LayoutProcessor
{
    protected $_context;
    protected $_citySource;
    protected $_streetSource;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \SR\Directory\Model\Config\Source\Cities $cities,
        \SR\Directory\Model\Config\Source\Streets $streets
    )
    {
        $this->_context = $context;
        $this->_citySource = $cities;
        $this->_streetSource = $streets;
    }

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


        return $jsLayout;
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
                    'description' => __('Start typing to search for your street. Add home number and floor if needed.'),
                ]
            ],
            'options' => [
                'sourceUrl' => $this->_context->getUrlBuilder()->getUrl('srdirectory/ajax/getstreets')
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