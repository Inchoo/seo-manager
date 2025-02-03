<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Controller\Adminhtml\Attribute;

class Options extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @param \Inchoo\Seo\Model\Catalog\Attribute\Option\Provider $attributeOptionsProvider
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Catalog\Attribute\Option\Provider $attributeOptionsProvider,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Get attribute options action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);

        if (!$attributeId = $this->getRequest()->getParam('attribute_id')) {
            return $resultJson->setData([]);
        }

        return $resultJson->setData($this->attributeOptionsProvider->get((int)$attributeId));
    }
}
