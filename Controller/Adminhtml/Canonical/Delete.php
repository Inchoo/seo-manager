<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Controller\Adminhtml\Canonical;

class Delete extends \Magento\Backend\App\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface,
    \Magento\Framework\App\Action\HttpGetActionInterface
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
     * The custom canonical delete action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('inchooseo/canonical/index');

        if (!$id = $this->getRequest()->getParam('id')) {
            $this->messageManager->addErrorMessage(__('Cannot find a custom canonical to delete.'));
            return $resultRedirect;
        }

        try {
            $this->customCanonicalRepository->deleteById((int)$id);
            $this->messageManager->addSuccessMessage(__('Custom canonical has been deleted.'));
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e);
        }

        return $resultRedirect;
    }
}
