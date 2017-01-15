<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\Directory\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    const CITY_LIST_FILE = 'israeliCityStreet.csv';
    const ISRAEL_COUNTRY_CODE = 'IL';

    protected $_csv;
    protected $_reader;

    public function __construct(\Magento\Framework\File\Csv $csv, \Magento\Framework\Module\Dir\Reader $reader)
    {
        $this->_csv = $csv;
        $this->_reader = $reader;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $file = $this->_reader->getModuleDir('etc', 'SR_Directory') . '/data/' . self::CITY_LIST_FILE;

        $data = $this->_csv->getData($file);

        //unset headers
        unset($data[0]);

        $city_symbol = null;

        foreach ($data as $row) {

            if ($row[0] != $city_symbol) {

                $city_symbol = $row[0];

                $cityInsert = [
                    'city_symbol' => $city_symbol,
                    'city_name' => $row[1],
                    'country_id' => self::ISRAEL_COUNTRY_CODE
                ];

                $setup->getConnection()->insertOnDuplicate($setup->getTable('studioraz_directory_city'), $cityInsert, ['city_name']);
            }

            $select = $setup->getConnection()->select()
                ->from($setup->getTable('studioraz_directory_city'), ['id'])
                ->where('city_symbol=' . $city_symbol)
                ->limit(1);

            $cityId = $setup->getConnection()->fetchOne($select);


            $streetInsert = [
                'city_id' => $cityId,
                'street_symbol' => $row[2],
                'street_name' => $row[3]
            ];
            $setup->getConnection()->insert($setup->getTable('studioraz_directory_street'), $streetInsert);
        }
    }
}