<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Controller\Adminhtml\Meta\Robots;

class ChangeStatus extends \Magento\Backend\App\Action implements
    \Magento\Framework\App\Action\HttpGetActionInterface,
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Inchoo_Seo::seo';

    /**
     * @param \Inchoo\Seo\Model\Meta\CustomRobotsRepository $customRobotsRepository
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Meta\CustomRobotsRepository $customRobotsRepository,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * The custom meta robots change status action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setRefererOrBaseUrl();

        if (!$id = $this->getRequest()->getParam('id')) {
            $this->messageManager->addErrorMessage(__('Cannot find the custom meta robots to change the status.'));
            return $resultRedirect;
        }

        try {
            $object = $this->customRobotsRepository->get((int)$id);
            $object->setStatus($object->getStatus() ? 0 : 1);
            $object = $this->customRobotsRepository->save($object);

            $status = $object->getStatus() ? 'enabled' : 'disabled';
            $this->messageManager->addSuccessMessage(__('The custom meta robots has been %1.', $status));
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e);
        }

        return $resultRedirect;
    }
}
