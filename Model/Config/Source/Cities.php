<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\Directory\Model\Config\Source;

class Cities extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    protected $_dbHelper;

    public function __construct(\SR\Directory\Helper\Db $dbHelper)
    {
        $this->_dbHelper = $dbHelper;
    }

    public function getAllOptions()
    {
        $cities = [];
        $cities[] = [
            'value' => '',
            'label' => 'Please Select',
        ];

        $values = $this->_dbHelper->getCities();
        foreach ($values as $value) {
            if (!empty(trim($value['city_name']))) {
                $cities[] = ['value' => $value['city_name'], 'label' => $value['city_name']];
            }
        }

        return $cities;
    }
}