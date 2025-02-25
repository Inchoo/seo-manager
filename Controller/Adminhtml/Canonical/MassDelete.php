<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Controller\Adminhtml\Canonical;

class MassDelete extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Inchoo_Seo::seo';

    /**
     * @param \Inchoo\Seo\Model\CustomCanonicalRepository $customCanonicalRepository
     * @param \Inchoo\Seo\Model\ResourceModel\CustomCanonical\CollectionFactory $customCanonicalCollectionFactory
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        protected \Inchoo\Seo\Model\CustomCanonicalRepository $customCanonicalRepository,
        protected \Inchoo\Seo\Model\ResourceModel\CustomCanonical\CollectionFactory $customCanonicalCollectionFactory,
        protected \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * The custom canonical mass delete action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->customCanonicalCollectionFactory->create());

            $count = 0;
            foreach ($collection->getItems() as $item) {
                $this->customCanonicalRepository->delete($item);
                $count++;
            }

            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $count));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Could not delete record(s)'));
        }

        return $this->resultRedirectFactory->create()->setPath('inchooseo/canonical/index');
    }
}
