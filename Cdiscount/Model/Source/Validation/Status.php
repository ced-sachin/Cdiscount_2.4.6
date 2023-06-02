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

namespace Ced\Cdiscount\Model\Source\Validation;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class Status
 *
 * @package Ced\Cdiscount\Model\Source
 */
class Status extends AbstractSource
{

    const INVALID = 'INVALID';
    const VALID = 'VALID';
    const NOT_VALIDATED = 'NOT_VALIDATED';
    const STATUS = [
        self::INVALID,
        self::VALID
    ];

    /**
     * @return array
     */
    public function getAllOptions()
    {
        return [
            [
                'value' => self::VALID,
                'label' => __('Valid'),
            ],
            [
                'value' => self::NOT_VALIDATED,
                'label' => __('not validated'),
            ],
            [
                'value' => self::INVALID,
                'label' => __('Invalid'),
            ]
        ];
    }
}
