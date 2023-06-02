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

namespace Ced\Cdiscount\Model\Source\FeedFilter;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class Status
 *
 * @package Ced\Cdiscount\Model\Source
 */
class Status extends AbstractSource
{
    const NOT_UPLOADED = 'NOT_UPLOADED';
    const LIVE = 'LIVE';
    const SUBMITTED = 'SUBMITTED';

    const STATUS = [
        self::NOT_UPLOADED,
        self::LIVE,
        self::SUBMITTED
    ];

    /**
     * @return array
     */
    public function getAllOptions()
    {
        return [
            [
                'value' => self::NOT_UPLOADED,
                'label' => __('Not Uploaded'),
            ],
            [
                'value' => self::LIVE,
                'label' => __('Live'),
            ],
            [
                'value' => self::SUBMITTED,
                'label' => __('Submitted')
            ]

        ];
    }
}
