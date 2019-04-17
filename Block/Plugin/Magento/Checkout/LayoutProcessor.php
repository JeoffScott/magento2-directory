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
     * LayoutProcessor constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \SR\Directory\Model\Config\Source\Cities $cities
     * @param \SR\Directory\Model\Config\Source\Streets $streets
     */
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


        $postCodeConfig = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode'];
        $postCodeConfig = $this->_getPostcodeComponentConfig($postCodeConfig);

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode'] = $postCodeConfig;

        foreach ($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                 ['payment']['children']['payments-list']['children'] as $key => $payment) {

            if (isset($payment['children']['form-fields']['children'])) {

                $cityConfig =  $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$key]['children']['form-fields']['children']['city'];

                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$key]['children']['form-fields']['children']['city'] =  $this->_getCityBillingComponentConfig($cityConfig);


                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$key]['children']['form-fields']['children']['street']['children'] = $_streetLines;

                $postCodeConfig = $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$key]['children']['form-fields']['children']['postcode'];

                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$key]['children']['form-fields']['children']['postcode'] =  $this->_getPostcodeBillingComponentConfig($postCodeConfig);
            }

        }

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
                    'description' => __('Start typing and choose a street from the list.'),
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
                    'description' => __('Start typing and choose a city from the list.'),
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

    /**
     * @param $cityConfig
     * @return array
     */
    private function _getCityBillingComponentConfig($cityConfig)
    {
        $_cityConfig = [
            'component' => 'SR_Base/js/form/element/autocomplete',
            'config' => [
                'customScope' => 'billingAddressfree',
                'elementTmpl' => 'SR_Base/form/element/autocomplete',
                'tooltip' => [
                    'description' => __('Start typing and choose a city from the list.'),
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
    /**
     * @param $postCodeConfig
     * @return array
     */
    private function _getPostcodeComponentConfig($postCodeConfig)
    {
        $_postCodeConfig = [
            'component' => 'SR_Directory/js/form/element/postcode',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'SR_Directory/form/element/postcode',
            ],
            'options' => [
                'sourceUrl' => $this->_context->getUrlBuilder()->getUrl('srdirectory/ajax/getpostcode'),
            ],
        ];

        return array_merge($postCodeConfig, $_postCodeConfig);
    }

    /**
     * @param $postCodeConfig
     * @return array
     */
    private function _getPostcodeBillingComponentConfig($postCodeConfig)
    {
        $_postCodeConfig = [
            'component' => 'SR_Directory/js/form/element/postcode',
            'config' => [
                'customScope' => 'billingAddressfree',
                'template' => 'ui/form/field',
                'elementTmpl' => 'SR_Directory/form/element/postcode',
            ],
            'options' => [
                'sourceUrl' => $this->_context->getUrlBuilder()->getUrl('srdirectory/ajax/getpostcode'),
            ],
        ];

        return array_merge($postCodeConfig, $_postCodeConfig);
    }
}
