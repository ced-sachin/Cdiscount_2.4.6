<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_Cdiscount
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Cdiscount\Ui\DataProvider\Product;

use Ced\Cdiscount\Model\ProfileProduct;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Api\FilterBuilder;
use Ced\Cdiscount\Model\Profile;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Class CdiscountProduct
 * @package Ced\Cdiscount\Ui\DataProvider\Product
 */
class CdiscountProduct extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    public $addFieldStrategies;

    /**
     * @var array
     */
    public $addFilterStrategies;

    /**
     * @var FilterBuilder
     */
    public $filterBuilder;

    public $pool;

    /** @var \Ced\Cdiscount\Model\ResourceModel\Profile\CollectionFactory $profileCollection */
    public $profileCollection;

    /**
     * @var Profile
     */
    public $profile;



    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        FilterBuilder $filterBuilder,
        \Magento\Ui\DataProvider\Modifier\PoolInterface $pool,
        \Ced\Cdiscount\Model\ResourceModel\Profile\CollectionFactory $profile,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->filterBuilder = $filterBuilder;
        $this->profileCollection = $profile;
        $this->pool = $pool;
        $this->collection = $collectionFactory->create()->addAttributeToSelect(['cdiscount_product_status']);
        $this->collection
            ->joinField(
                'qty',
                'cataloginventory_stock_item',
                'qty',
                'product_id = entity_id',
                '{{table}}.stock_id=1',
                null
            );
//        $this->collection->setStoreId($config->getStore())
//        ->joinAttribute(
//            'cdiscount_product_status',
//            'catalog_product/cdiscount_product_status',
//            'entity_id',
//            null,
//            'left'
//        );
        $this->addField('cdiscount_profile_id');
        //$this->addField('cdiscount_product_status');
        $this->addField('cdiscount_validation_errors');
        $this->addField('cdiscount_feed_errors');

        $ids = $this->profileCollection->create()->getAllIds();

        $this->addFilter(
            $this->filterBuilder->setField('cdiscount_profile_id')->setConditionType('in')
                ->setValue($ids)
                ->create()
        );

        $this->addFilter(
            $this->filterBuilder->setField('type_id')->setConditionType('in')
                ->setValue(['simple','configurable'])
                ->create()
        );
        $this->addFilter(
            $this->filterBuilder->setField('visibility')->setConditionType('nin')
                ->setValue([1])
                ->create()
        );
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
    }

    public function addField($field, $alias = null)
    {
        if (isset($this->addFieldStrategies[$field])) {
            $this->addFieldStrategies[$field]->addField($this->getCollection(), $field, $alias);
        } else {
            parent::addField($field, $alias);
        }
    }

    /**
     * @param \Magento\Framework\Api\Filter $filter
     * @return void
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if($filter->getField() == 'cdiscount_validation_errors') {

            if ($filter->getValue() == 'VALID') {
                $filterData = array(
                    array(
                        'attribute' => $filter->getField(),
                        'eq' => '["valid"]')
                );
            } elseif ($filter->getValue() == 'INVALID') {
                $filterData = array(
                    array(
                        'attribute' => $filter->getField(),
                        'neq' => '["valid"]')
                );
            }
            elseif( $filter->getValue() == 'NOT_VALIDATED' ) {
                $filterData = array(
                    array(
                        'attribute' => $filter->getField(),
                        'null' => true)
                );
            }
            $this->getCollection()->addAttributeToFilter($filterData);
        }
        elseif($filter->getField() == 'cdiscount_feed_errors') {
            //var_dump($filter->getValue()); die;
            if ($filter->getValue() == 'LIVE') {
                $filterData = array(
                    array(
                        'attribute' => 'cdiscount_product_status',
                        'eq' => 'LIVE')
                );
            } elseif ($filter->getValue() == 'SUBMITTED') {
                $filterData = array(
                    array(
                        'attribute' => 'cdiscount_product_status',
                        'eq' => 'SUBMITTED' )
                );
            }
            elseif( $filter->getValue() == 'NOT_UPLOADED' ) {
                $filterData = array(
                    array(
                        'attribute' => 'cdiscount_product_status',
                        'eq' => 'NOT_UPLOADED')
                );
            }
            $this->getCollection()->addAttributeToFilter($filterData);
        }
        elseif (isset($this->addFilterStrategies[$filter->getField()])) {
            $this->addFilterStrategies[$filter->getField()]
                ->addFilter(
                    $this->getCollection(),
                    $filter->getField(),
                    [$filter->getConditionType() => $filter->getValue()]
                );
        }
        else {
//            print_r($filter->getValue());  die('<-- REQ2');
            parent::addFilter($filter);
        }
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getData()
    {
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }

        $data = $this->getCollection()->toArray();
        /*foreach ($this->pool->getModifiersInstances() as $modifier) {
            $data = $modifier->modifyData($data);
        }*/
        return [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => array_values($data),
        ];
    }
}
