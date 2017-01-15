<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\Directory\Helper;

class Db extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_connection;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Framework\App\ResourceConnection $connection)
    {
        $this->_connection = $connection->getConnection();
        parent::__construct($context);
    }

    public function getCities()
    {
        $select = $this->_connection->select()->from($this->_connection->getTableName('studioraz_directory_city'));
        return $this->_connection->fetchAll($select);
    }

    public function getStreets($cityId, $fieldName = 'city_name')
    {
        $streetTable = $this->_connection->getTableName('studioraz_directory_street');
        $cityTable = $this->_connection->getTableName('studioraz_directory_city');
        $select = $this->_connection->select()->from($streetTable);

        if ($cityId) {
            $select->joinInner($cityTable, $streetTable . '.city_id = ' . $cityTable . '.id', []);
            $select->where($cityTable . '.' . $fieldName . ' = "' . $cityId . '"');
        }

        return $this->_connection->fetchAll($select);
    }
}