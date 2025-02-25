<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Controller\Adminhtml\Meta\Robots;

class Delete extends \Magento\Backend\App\Action
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
     * The custom meta robots delete action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            if ($id = $this->getRequest()->getParam('id')) {
                $this->customRobotsRepository->deleteById((int)$id);
                $this->messageManager->addSuccessMessage(__('Entry successfully deleted.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e);
            return $resultRedirect->setRefererOrBaseUrl();
        }

        return $resultRedirect->setPath('inchooseo/meta_robots/index');
    }
}
