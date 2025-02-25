<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Controller\Adminhtml\Canonical;

class Edit extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Inchoo_Seo::seo';

    /**
     * @param \Inchoo\Seo\Model\CustomCanonicalRepository $customCanonicalRepository
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        protected \Inchoo\Seo\Model\CustomCanonicalRepository $customCanonicalRepository,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * The custom canonical form action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);

        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $this->customCanonicalRepository->get((int)$id);
                $resultPage->getConfig()->getTitle()->set(__('Edit Custom Canonical'));
                return $resultPage;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Custom canonical does not exist.'));
                return $this->resultRedirectFactory->create()->setPath('inchooseo/canonical/index');
            }
        }

        $resultPage->getConfig()->getTitle()->set(__('New Custom Canonical'));
        return $resultPage;
    }
}
