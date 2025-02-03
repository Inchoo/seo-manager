<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Controller\Adminhtml\Canonical;

class Save extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Inchoo_Seo::seo';

    /**
     * @param \Inchoo\Seo\Model\CustomCanonicalRepository $customCanonicalRepository
     * @param \Inchoo\Seo\Model\CustomCanonicalFactory $customCanonicalFactory
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        protected \Inchoo\Seo\Model\CustomCanonicalRepository $customCanonicalRepository,
        protected \Inchoo\Seo\Model\CustomCanonicalFactory $customCanonicalFactory,
        protected \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * The custom canonical save action.
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
            if ($entityId = $this->getRequest()->getParam('entity_id')) {
                $model = $this->customCanonicalRepository->get((int)$entityId);
            } else {
                $model = $this->customCanonicalFactory->create();
            }

            $model->setStatus($request->getParam('status'));
            $model->setStoreId($request->getParam('store_id'));
            $model->setRequestPath($request->getParam('request_path'));
            $model->setTargetPath($request->getParam('target_path'));

            $model = $this->customCanonicalRepository->save($model);
            $this->messageManager->addSuccessMessage('The custom canonical has been successfully saved.');
        } catch (\Exception $e) {
            $this->dataPersistor->set('custom_canonical', $request->getParams());
            $this->messageManager->addExceptionMessage($e);
            return $resultRedirect->setRefererOrBaseUrl();
        }

        return $resultRedirect->setPath('inchooseo/canonical/edit', ['id' => $model->getId()]);
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
            && $request->getParam('request_path')
            && $request->getParam('target_path');
    }
}
