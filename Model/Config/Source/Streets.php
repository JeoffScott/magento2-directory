<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\Directory\Model\Config\Source;

class Streets
{
    protected $_dbHelper;

    public function __construct(\SR\Directory\Helper\Db $dbHelper)
    {
        $this->_dbHelper = $dbHelper;
    }

    public function getAllOptions($cityName)
    {
        $streets = [];
        $streets[] = [
            'value' => '',
            'label' => 'Please Select',
        ];

        $values = $this->_dbHelper->getStreets($cityName);
        foreach ($values as $value) {
            if (!empty(trim($value['street_name']))) {
                $streets[] = ['value' => $value['street_name'], 'label' => $value['street_name'], 'id' => $value['id']];
            }
        }

        return $streets;
    }
}