<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Controller\Adminhtml\Meta\Rule\Product;

class Save extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Inchoo_Seo::seo';

    /**
     * @param \Inchoo\Seo\Model\Meta\ProductRuleRepository $productRuleRepository
     * @param \Inchoo\Seo\Model\Meta\ProductRuleFactory $productRuleFactory
     * @param \Inchoo\Seo\Model\ResourceModel\Meta\ProductRule $productRuleResource
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Meta\ProductRuleRepository $productRuleRepository,
        protected \Inchoo\Seo\Model\Meta\ProductRuleFactory $productRuleFactory,
        protected \Inchoo\Seo\Model\ResourceModel\Meta\ProductRule $productRuleResource,
        protected \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * The product meta rule save action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('inchooseo/meta_rule_product/index');

        if (!$data = $this->getRequest()->getParams()) {
            return $resultRedirect;
        }

        try {
            if ($entityId = $this->getRequest()->getParam('entity_id')) {
                $model = $this->productRuleRepository->get((int)$entityId);
            } else {
                $model = $this->productRuleFactory->create();
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('The product meta rule no longer exists.'));
            return $resultRedirect;
        }

        $model->setData($this->prepareData($data));
        $model->setAttributeOptions($model->getData('attribute_options'));

        // Rule needs to be unique
        if (!$this->productRuleResource->isRuleUnique($model)) {
            $this->dataPersistor->set('product_meta_rule', $data);
            $this->messageManager->addErrorMessage(__('A product rule with the same conditions already exists.'));
            return $resultRedirect->setPath('inchooseo/meta_rule_product/edit', ['id' => $model->getId()]);
        }

        try {
            $model = $this->productRuleRepository->save($model);
            $this->messageManager->addSuccessMessage(__('The product meta rule was saved successfully.'));
        } catch (\Exception $e) {
            $this->dataPersistor->set('product_meta_rule', $data);
            $this->messageManager->addExceptionMessage($e);
            $resultRedirect->setPath('inchooseo/meta_rule_product/edit', ['id' => $model->getId()]);
        }

        // Save and Continue Edit
        if ($this->getRequest()->getParam('back')) {
            $resultRedirect->setPath('inchooseo/meta_rule_product/edit', ['id' => $model->getId()]);
        }

        return $resultRedirect;
    }

    /**
     * Prepare the product meta rule data.
     *
     * @param array $postData
     * @return array
     */
    protected function prepareData(array $postData): array
    {
        $attributeOptions = [];
        foreach ($postData['attribute_rows'] ?? [] as $attributeRow) {
            if (isset($attributeRow['attribute'], $attributeRow['option'])
                && $attributeRow['attribute']
                && $attributeRow['option']
            ) {
                $uniqueKey = "{$attributeRow['attribute']}_{$attributeRow['option']}";
                $attributeOptions[$uniqueKey] = [
                    'attribute' => $attributeRow['attribute'],
                    'option' => $attributeRow['option']
                ];
            }
        }

        $postData['attribute_options'] = $attributeOptions ? array_values($attributeOptions) : null;

        return $postData;
    }
}
