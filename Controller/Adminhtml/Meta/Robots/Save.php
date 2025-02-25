<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Controller\Adminhtml\Meta\Robots;

class Save extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Inchoo_Seo::seo';

    /**
     * @param \Inchoo\Seo\Model\Meta\CustomRobotsRepository $customRobotsRepository
     * @param \Inchoo\Seo\Model\Meta\CustomRobotsFactory $customRobotsFactory
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Meta\CustomRobotsRepository $customRobotsRepository,
        protected \Inchoo\Seo\Model\Meta\CustomRobotsFactory $customRobotsFactory,
        protected \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * The custom meta robots save action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $request = $this->getRequest();

        if (!$this->validateRequest($request)) {
            $this->messageManager->addErrorMessage('Please ensure all required fields are filled.');
            return $resultRedirect->setRefererOrBaseUrl();
        }

        try {
            if ($entityId = $request->getParam('entity_id')) {
                $model = $this->customRobotsRepository->get((int)$entityId);
            } else {
                $model = $this->customRobotsFactory->create();
            }

            $model->setStatus($request->getParam('status'));
            $model->setStoreId($request->getParam('store_id'));
            $model->setUrlPath($request->getParam('url_path'));
            $model->setMetaRobots($request->getParam('meta_robots'));
            $model->setSortOrder($request->getParam('sort_order'));

            $this->customRobotsRepository->save($model);
            $this->messageManager->addSuccessMessage(__('The custom meta robots has been successfully saved.'));
        } catch (\Exception $e) {
            $this->dataPersistor->set('custom_meta_robots', $request->getParams());
            $this->messageManager->addExceptionMessage($e);
            return $resultRedirect->setRefererOrBaseUrl();
        }

        return $resultRedirect->setPath('inchooseo/meta_robots/edit', ['id' => $model->getEntityId()]);
    }

    /**
     * Validate the save request.
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */
    protected function validateRequest(\Magento\Framework\App\RequestInterface $request): bool
    {
        return $request->getParam('store_id') !== null
            && $request->getParam('url_path')
            && $request->getParam('meta_robots');
    }
}
