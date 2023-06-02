<?php
/**
 * Created by CDiscount
 * Created by CDiscount
 * Date: 08/11/2016
 * Time: 15:23
 */

namespace Sdk\Soap\Offer;


use Sdk\Soap\BaliseTool;

class SubmitOfferStateAction extends BaliseTool
{

    private $productPackageRequestTAG = 'offerStateRequest';

    private $action = 'Action';
    private $productId = 'SellerProductId';

    public function __construct($xmlns = 'xmlns="http://www.cdiscount.com"')
    {
        $this->_xmlns = $xmlns;
        $this->_tag = 'SubmitOfferStateAction';
        parent::__construct();
    }

    /**
     * @param $zipURL
     * @return string XML
     */
    public function generatePackageRequestXML($productUpdate, $status)
    {
        $xml = '';
        foreach ($productUpdate as $sku){
        $xml .= $this->_xmlUtil->generateOpenBalise($this->productPackageRequestTAG);

        $xml .= $this->_xmlUtil->generateBalise($this->action, $status);
        $xml .= $this->_xmlUtil->generateBalise($this->productId, $sku);

        $xml .= $this->_xmlUtil->generateCloseBalise($this->productPackageRequestTAG);
    
        }
        
        return $xml;
    }
}