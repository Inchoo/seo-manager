<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\ResourceModel\Meta;

class CategoryRule extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @param \Inchoo\Seo\Helper\Data $dataHelper
     * @param \Magento\Framework\ValidatorFactory $validatorFactory
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param string|null $connectionName
     */
    public function __construct(
        protected \Inchoo\Seo\Helper\Data $dataHelper,
        protected \Magento\Framework\ValidatorFactory $validatorFactory,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        ?string $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * Perform actions before object save.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('inchoo_seo_category_meta_rule', 'entity_id');
    }

    /**
     * Perform actions before object save.
     *
     * @param \Magento\Framework\Model\AbstractModel|\Inchoo\Seo\Model\Meta\CategoryRule $object
     * @return self
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getStoreId() === null) {
            $object->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID);
        }

        if (!$object->getCategoryId()) {
            $object->setCategoryId(null);
        }

        if (is_array($object->getData('attribute_options'))) {
            $object->setAttributeOptions($object->getData('attribute_options'));
        }

        if (!$object->getCategoryId()) {
            $object->setCanBeFallback(0);
        }

        if (!$object->getAttributeOptionsData()) {
            $object->setAllowAdditionalAttributes(0);
        }

        if (!$object->getCategoryId() || count($object->getAttributeOptionsData()) !== 1) {
            $object->setAddToSitemap(0);
        }

        if (!$object->getAttributeOptionsData() || $object->getCanonical()) {
            $object->setUseAttributesInCanonical(0);
        }

        return $this;
    }

    /**
     * Validate the object before saving.
     *
     * @return \Magento\Framework\Validator\ValidatorInterface
     */
    public function getValidationRulesBeforeSave()
    {
        $data = [
            'callback' => [$this, 'isCategoryRuleUnique'],
            'errorMessage' => __('Category rule with the same conditions already exists.')
        ];
        return $this->validatorFactory->create($data, \Inchoo\Seo\Model\Validator\CallbackValidator::class);
    }

    /**
     * Check if the category meta rule is unique.
     *
     * @param \Magento\Framework\Model\AbstractModel|\Inchoo\Seo\Model\Meta\CategoryRule $object
     * @return bool
     * @throws \JsonException
     */
    public function isCategoryRuleUnique(\Magento\Framework\Model\AbstractModel $object): bool
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), ['entity_id', 'attribute_options']);
        $select->where('store_id = ?', $object->getStoreId() ?: \Magento\Store\Model\Store::DEFAULT_STORE_ID);

        if ($object->getEntityId()) {
            $select->where("{$this->getIdFieldName()} != ?", $object->getEntityId());
        }

        if ($categoryId = $object->getCategoryId()) {
            $select->where('category_id = ?', $categoryId);
        } else {
            $select->where('category_id IS NULL');
        }

        if (!$attributeIds = $object->getAttributeIds()) {
            $select->where('attribute_ids IS NULL');
            return !$this->getConnection()->fetchRow($select);
        }

        $select->where('attribute_ids = ?', $attributeIds);

        $newAttributeOptionsString = $this->dataHelper->stringifyAttributeOptions(
            $object->getAttributeOptionsData()
        );

        $stmt = $this->getConnection()->query($select);
        while ($row = $stmt->fetch()) {
            $rowAttributesOptionsData = json_decode($row['attribute_options'], true, 512, JSON_THROW_ON_ERROR);
            $rowAttributeOptionsString = $this->dataHelper->stringifyAttributeOptions($rowAttributesOptionsData);
            if ($rowAttributeOptionsString === $newAttributeOptionsString) {
                return false;
            }
        }

        return true;
    }
}
