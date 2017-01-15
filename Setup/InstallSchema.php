<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\Directory\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    const CITY_LIST_FILE = 'IsraeliAddress.csv';
    const ISRAEL_COUNTRY_CODE = 'IL';

    protected $_csv;
    protected $_reader;

    public function __construct(\Magento\Framework\File\Csv $csv, \Magento\Framework\Module\Dir\Reader $reader)
    {
        $this->_csv = $csv;
        $this->_reader = $reader;
    }

    public function install(SchemaSetupInterface $installer, ModuleContextInterface $context)
    {

        $installer->startSetup();

        /**
         * Create city table
         */

        $table = $installer->getConnection()->newTable(
            $installer->getTable('studioraz_directory_city')
        )->addColumn(
            'id', Table::TYPE_INTEGER, null, ['primary' => true, 'nullable' => false, 'identity' => true]
        )->addColumn(
            'city_symbol', Table::TYPE_TEXT, 4, ['nullable' => false]
        )->addColumn(
            'city_name', Table::TYPE_TEXT, 60, []
        )->addColumn(
            'country_id', Table::TYPE_TEXT, 2, ['nullable' => false]
        )->addForeignKey(
            $installer->getFkName(
                'studioraz_directory_country_city_key_name',
                'country_id',
                'directory_country',
                'country_id'
            ),
            'country_id',
            $installer->getTable('directory_country'),
            'country_id'
        )->addIndex(
            $installer->getIdxName(
                $installer->getTable('studioraz_directory_city'), ['city_symbol', 'city_name'], \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['city_symbol', 'city_name'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        );

        $installer->getConnection()->createTable($table);


        /**
         * Create street table
         */

        $table = $installer->getConnection()->newTable(
            $installer->getTable('studioraz_directory_street')
        )->addColumn(
            'id', Table::TYPE_INTEGER, null, ['primary' => true, 'nullable' => false, 'identity' => true]
        )->addColumn(
            'city_id', Table::TYPE_INTEGER, null, ['nullable' => false]
        )->addColumn(
            'street_symbol', Table::TYPE_TEXT, 5, ['nullable' => false]
        )->addColumn(
            'street_name', Table::TYPE_TEXT, 60, []
        )->addForeignKey(
            $installer->getFkName(
                'studioraz_directory_street_key_name',
                'city_id',
                'studioraz_directory_city',
                'id'
            ),
            'city_id',
            $installer->getTable('studioraz_directory_city'),
            'id'
        );

        $installer->getConnection()->createTable($table);


        $installer->endSetup();

    }

}