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
 * @category  Ced
 * @package   Ced_Cdiscount
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Cdiscount\Ui\Component\Listing\Columns\Feeds;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class AdvancedActions extends Column
{
    /**
     * Url path
     */
    const URL_PATH_SYNC = 'cdiscount/feeds/sync';
    const URL_PATH_DELETE = 'cdiscount/feeds/delete';

    /**
     * @var UrlBuilder
     */
    protected $actionUrlBuilder;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlBuilder         $actionUrlBuilder
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    /**
     * Prepare Data Source
     *
     * @param  array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item['id'])) {
                    if (isset($item['sync_url']) && !empty($item['sync_url'])) {
                        if($item["type"] == "product_creation") {
                            $item[$name]['sync_url'] = [
                                'href' =>
                                    $this->urlBuilder->getUrl(self::URL_PATH_SYNC,
                                        ['id' => $item['id'], 'resync' => true]),
                                'label' => __('Sync'),
                                'class' => 'cedcommerce actions sync'
                            ];
                        }
                    }
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::URL_PATH_DELETE, ['id' => $item['id']]),
                        'label' => __('Delete'),
                        'title' => __('Feed') . ' #'.$item['feed_id'],
                        'confirm' => [
                            'title' => __('Delete feed'). ' #'.$item['feed_id'],
                            'message' => __('Are you sure you wan\'t to delete the feed?')
                        ],
                        'class' => 'cedcommerce actions delete'
                    ];
                }
            }
        }
        return $dataSource;
    }
}
