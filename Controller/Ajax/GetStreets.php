<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\Directory\Controller\Ajax;

class GetStreets extends \Magento\Framework\App\Action\Action
{
    protected $_jsonFactory;
    protected $_streetSource;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \SR\Directory\Model\Config\Source\Streets $streets
    )
    {
        $this->_jsonFactory = $jsonFactory;
        $this->_streetSource = $streets;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->_jsonFactory->create();
        if ($this->getRequest()->isAjax()) {
            $data = $this->_streetSource->getAllOptions($this->getRequest()->getParam('city'));
            return $result->setData($data);
        }
    }
}