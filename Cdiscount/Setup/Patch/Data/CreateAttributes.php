<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Ced\Cdiscount\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
* Patch is mechanism, that allows to do atomic upgrade data changes
*/
class CreateAttributes implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;
    private $eavSetupFactory;
    private $eavAttribute;
    private $objectManager;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $eavAttribute
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->objectManager = $objectManager;
        $this->eavAttribute = $eavAttribute;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        /**
         * @var EavSetup $eavSetup
         */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        /**
         * Add attributes to the eav/attribute
         */
        $groupName = 'Cdiscount';
        $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSetId = $eavSetup->getDefaultAttributeSetId($entityTypeId);
        $eavSetup->addAttributeGroup($entityTypeId, $attributeSetId, $groupName, 1000);
        $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);

        if (!$this->eavAttribute->getIdByCode('catalog_product', 'cdiscount_product_status')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'cdiscount_product_status',
                [
                    'group' => 'Cdiscount',
                    'note' => 'product status on Cdiscount',
                    'input' => 'select',
                    'source' => 'Ced\Cdiscount\Model\Source\Product\Status',
                    'type' => 'varchar',
                    'label' => 'Cdiscount Product Status',
                    'backend' => '',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 9,
                    'user_defined' => 1,
                    'searchable' => 0,
                    'visible_on_front' => 0,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }

        if (!$this->eavAttribute->getIdByCode('catalog_product', 'short_label')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'short_label',
                [
                    'group' => 'Cdiscount',
                    'note' => "Please enter package description",
                    'input' => 'text',
                    'type' => 'text',
                    'label' => 'Short Label',
                    'backend' => '',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 10,
                    'user_defined' => 1,
                    'searchable' => 0,
                    'filterable' => 0,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }

        if (!$this->eavAttribute->getIdByCode('catalog_product', 'cdiscount_profile_id')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'cdiscount_profile_id',
                [
                    'group' => 'Cdiscount',
                    'input' => 'text',
                    'type' => 'varchar',
                    'label' => 'Cdiscount Profile Id',
                    'backend' => '',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 1,
                    'user_defined' => 1,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }

        if (!$this->eavAttribute->getIdByCode('catalog_product', 'cdiscount_validation_errors')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'cdiscount_validation_errors',
                [
                    'group' => 'Cdiscount',
                    'note' => "Cdiscount Validation Errors",
                    'input' => 'text',
                    'type' => 'text',
                    'label' => 'Cdiscount Validation Errors',
                    'backend' => '',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 14,
                    'user_defined' => 1,
                    'searchable' => 1,
                    'filterable' => 0,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }

        if (!$this->eavAttribute->getIdByCode('catalog_product', 'cdiscount_feed_errors')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'cdiscount_feed_errors',
                [
                    'group' => 'Cdiscount',
                    'note' => "Cdiscount Feed Errors",
                    'input' => 'text',
                    'type' => 'text',
                    'label' => 'Cdiscount Feed Errors',
                    'backend' => '',
                    'visible' => 1,
                    'required' => 0,
                    'sort_order' => 17,
                    'user_defined' => 1,
                    'searchable' => 1,
                    'filterable' => 0,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }

        if (!$this->eavAttribute->getIdByCode('catalog_product', 'cdiscount_feed_product')) {
            $eavSetup->addAttribute(
                'catalog_product',
                'cdiscount_feed_product',
                [
                    'group' => $groupName,
                    'note' => 'Cdiscount Feed Products',
                    'input' => 'text',
                    'type' => 'varchar',
                    'label' => 'Cdiscount Feed Products ',
                    'backend' => '',
                    'visible' => false,
                    'required' => 0,
                    'sort_order' => 1,
                    'user_defined' => 1,
                    'comparable' => 0,
                    'visible_on_front' => 0,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                ]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [

        ];
    }
}
