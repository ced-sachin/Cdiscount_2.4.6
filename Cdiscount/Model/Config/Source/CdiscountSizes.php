<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_Lazada
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright © 2019 CedCommerce. All rights reserved.
 * @license     EULA http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Cdiscount\Model\Config\Source;

use Ced\Cdiscount\Model\Sizes;

class CdiscountSizes implements \Magento\Framework\Data\OptionSourceInterface
{
    public $directoryList;
    public $parser;
    public $sizes;
    public $collectionFactory;

    public function __construct(
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Xml\ParserFactory $parser,
        \Ced\Cdiscount\Model\ResourceModel\Sizes\CollectionFactory $collectionFactory,
        \Ced\Cdiscount\Model\SizesFactory $sizes
    ) {
        $this->directoryList = $directoryList;
        $this->parser = $parser;
        $this->sizes=$sizes;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function toOptionArray()
    {
        $returnData = [];
        if (empty($this->collectionFactory->create()->getSize())) {
            $codeDir = $this->directoryList->getPath('app');
            $compDir = "{$codeDir}/code/Ced/Cdiscount/etc/cdiscount/size.xml";
            $arr = $this->parser->create()->load($compDir)->xmlToArray();
            foreach ($arr['VariationValueList']['VariationDescription'] as $value) {
                $this->sizes->create()->setData('size', $value['VariantValueDescription']);
                $this->sizes->save();
            }
        }
        foreach ($this->collectionFactory->create()->load()->getData() as $datum) {
            $returnData[] = [
                'label' => $datum['size'],
                'value' => $datum['size']
            ];
        }
        return $returnData;
    }
}
