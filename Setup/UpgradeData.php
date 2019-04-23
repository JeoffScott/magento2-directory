<?php

/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\Directory\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;

class UpgradeData implements UpgradeDataInterface
{

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function upgrade( ModuleDataSetupInterface $setup, ModuleContextInterface $context ) {

        $attributeCode = 'house_number';

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        if ( version_compare( $context->getVersion(), '1.0.1', '<' ) ) {


            $customerSetup->addAttribute('customer_address', $attributeCode, [
                'label' => 'House Number',
                'input' => 'text',
                'type' => 'varchar',
                'source' => '',
                'required' => false,
                'position' => 333,
                'visible' => true,
                'system' => false,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'backend' => ''
            ]);


            $attribute = $customerSetup->getEavConfig()->getAttribute('customer_address', $attributeCode)
                ->addData(['used_in_forms' => [
                    'customer_address_edit',
                    'customer_register_address'
                ]]);
            $attribute->save();

            $setup->getConnection()->addColumn(
                $setup->getTable('quote_address'),
                $attributeCode,
                [
                    'type' => 'text',
                    'length' => 10,
                    'comment' => 'House Number'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order_address'),
                $attributeCode,
                [
                    'type' => 'text',
                    'length' => 10,
                    'comment' => 'House Number'
                ]
            );
        }

        if ( version_compare( $context->getVersion(), '1.0.2', '<' ) )  {
            $attribute = $customerSetup->getEavConfig()->getAttribute('customer_address', $attributeCode)
                ->addData(['used_in_forms' => [
                    'adminhtml_customer_address',
                    'adminhtml_checkout',
                    'customer_address'
                ]]);
            $attribute->save();
        }

        if ( version_compare( $context->getVersion(), '1.0.3', '<' ) )  {
            $this->createEntranceAttribute($setup,$customerSetup);

        }

    }

    public function createEntranceAttribute(ModuleDataSetupInterface $setup,$customerSetup)
    {
        $attributeCode = 'entrance';

        $customerSetup->addAttribute('customer_address', $attributeCode, [
            'label' => 'Entrance',
            'input' => 'select',
            'type' => 'varchar',
            'source' => 'SR\Directory\Model\Source\Entrance',
            'required' => false,
            'position' => 334,
            'visible' => true,
            'system' => false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false,
            'backend' => ''
        ]);


        $attribute = $customerSetup->getEavConfig()->getAttribute('customer_address', $attributeCode)
            ->addData(['used_in_forms' => [
                'customer_address_edit',
                'customer_register_address',
                'adminhtml_customer_address',
                'adminhtml_checkout',
                'customer_address'
            ]]);
        $attribute->save();

        $setup->getConnection()->addColumn(
            $setup->getTable('quote_address'),
            $attributeCode,
            [
                'type' => 'text',
                'length' => 10,
                'comment' => 'Entrance'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_address'),
            $attributeCode,
            [
                'type' => 'text',
                'length' => 10,
                'comment' => 'Entrance'
            ]
        );
    }
}