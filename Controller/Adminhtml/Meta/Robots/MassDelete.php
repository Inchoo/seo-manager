<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Controller\Adminhtml\Meta\Robots;

class MassDelete extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Inchoo_Seo::seo';

    /**
     * @param \Inchoo\Seo\Model\Meta\CustomRobotsRepository $customRobotsRepository
     * @param \Inchoo\Seo\Model\ResourceModel\Meta\CustomRobots\CollectionFactory $customRobotsCollectionFactory
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Meta\CustomRobotsRepository $customRobotsRepository,
        protected \Inchoo\Seo\Model\ResourceModel\Meta\CustomRobots\CollectionFactory $customRobotsCollectionFactory,
        protected \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * The custom meta robots mass delete action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->customRobotsCollectionFactory->create());

            $count = 0;
            foreach ($collection->getItems() as $item) {
                $this->customRobotsRepository->delete($item);
                $count++;
            }

            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $count));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Could not delete record(s)'));
        }

        return $this->resultRedirectFactory->create()->setPath('inchooseo/meta_robots/index');
    }
}
