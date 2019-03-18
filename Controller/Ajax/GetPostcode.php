<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\Directory\Controller\Ajax;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class GetPostcode extends \Magento\Framework\App\Action\Action
{
    protected $jsonFactory;
    const API_URL = 'https://www.israelpost.co.il/zip_data.nsf/SearchZip';

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory
    )
    {
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();
        $params = $this->getRequest()->getParams();
        $resultData = [];

        if ($this->getRequest()->isAjax()) {

            if($curl = curl_init()){
                curl_setopt($curl, CURLOPT_URL, self::API_URL . '?'. http_build_query($params));
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);

                $dom = new \DOMDocument();
                $dom->loadHTML(curl_exec($curl));
                $resultData['postcode'] = trim($dom->getElementsByTagName('body')->item(0)->textContent);
            }
        }

        return $result->setData($resultData);
    }
}