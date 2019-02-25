<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\Directory\Controller\Ajax;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\HTTP\Client\Curl;

class GetPostcode extends \Magento\Framework\App\Action\Action
{
    protected $jsonFactory;
    protected $curl;
    const API_URL = 'https://www.israelpost.co.il/zip_data.nsf/SearchZip';

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        Curl $curl
    )
    {
        $this->curl = $curl;
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();
        $params = $this->getRequest()->getParams();
        $resultData = [];

        if ($this->getRequest()->isAjax()) {

            $this->getCurlClient()->get(self::API_URL . '?'. http_build_query($params));
            $this->getCurlClient()->setOption(CURLOPT_RETURNTRANSFER, 1);

            $resultData['postcode'] = $this->curl->getBody();

            return $result->setData($resultData);
        }
    }

    /**
     * @return Curl
     */
    protected function getCurlClient()
    {
        return $this->curl;
    }
}