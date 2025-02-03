<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Controller\Adminhtml\Meta\Rule\Category;

class Save extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Inchoo_Seo::seo';

    /**
     * @param \Inchoo\Seo\Model\Meta\CategoryRuleRepository $categoryRuleRepository
     * @param \Inchoo\Seo\Model\Meta\CategoryRuleFactory $categoryRuleFactory
     * @param \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule $categoryRuleResource
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Meta\CategoryRuleRepository $categoryRuleRepository,
        protected \Inchoo\Seo\Model\Meta\CategoryRuleFactory $categoryRuleFactory,
        protected \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule $categoryRuleResource,
        protected \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * The category meta rule save action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('inchooseo/meta_rule_category/index');

        if (!$data = $this->getRequest()->getParams()) {
            return $resultRedirect;
        }

        try {
            if ($entityId = $this->getRequest()->getParam('entity_id')) {
                $model = $this->categoryRuleRepository->get((int)$entityId);
            } else {
                $model = $this->categoryRuleFactory->create();
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('The Category meta rule no longer exists.'));
            return $resultRedirect;
        }

        $model->setData($this->prepareData($data));
        $model->setAttributeOptions($model->getData('attribute_options'));

        try {
            $model = $this->categoryRuleRepository->save($model);
            $this->messageManager->addSuccessMessage(__('The category meta rule has been saved successfully.'));
        } catch (\Exception $e) {
            $this->dataPersistor->set('category_meta_rule', $data);
            $this->messageManager->addExceptionMessage($e);
            return $resultRedirect->setRefererUrl();
        }

        // save and continue edit
        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath('inchooseo/meta_rule_category/edit', ['id' => $model->getId()]);
        }

        return $resultRedirect;
    }

    /**
     * Prepare the category meta rule data.
     *
     * @param array $data
     * @return array
     */
    protected function prepareData(array $data): array
    {
        $attributeOptions = [];
        foreach ($data['attribute_rows'] ?? [] as $attributeRow) {
            if (isset($attributeRow['attribute'], $attributeRow['option']) && $attributeRow['attribute']) {
                $uniqueKey = "{$attributeRow['attribute']}_{$attributeRow['option']}";
                $attributeOptions[$uniqueKey] = [
                    'attribute' => $attributeRow['attribute'],
                    'option' => $attributeRow['option']
                ];
            }
        }

        $data['attribute_options'] = $attributeOptions ? array_values($attributeOptions) : null;

        return $data;
    }
}
