<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Controller\Adminhtml\Meta\Robots;

class Edit extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
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
     * The custom meta robots form action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);

        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $this->customRobotsRepository->get((int)$id);
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Meta Robots'));
                return $resultPage;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('The custom meta robots item does not exist.'));
                return $this->resultRedirectFactory->create()->setRefererOrBaseUrl();
            }
        }

        $resultPage->getConfig()->getTitle()->prepend(__('Add New Meta Robots'));
        return $resultPage;
    }
}
