<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Meta\Fallback;

class ProductRuleQueue
{
    /**
     * @param \Inchoo\Seo\Model\Meta\ProductRuleRepository $productMetaRuleRepository
     * @param \Inchoo\Seo\Model\ResourceModel\ProductCategoryList $productCategoryList
     * @param \Inchoo\Seo\Model\ResourceModel\Meta\ProductRule\CollectionFactory $metaRuleCollectionFactory
     * @param \SplPriorityQueueFactory $splPriorityQueueFactory
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Meta\ProductRuleRepository $productMetaRuleRepository,
        protected \Inchoo\Seo\Model\ResourceModel\ProductCategoryList $productCategoryList,
        protected \Inchoo\Seo\Model\ResourceModel\Meta\ProductRule\CollectionFactory $metaRuleCollectionFactory,
        protected \SplPriorityQueueFactory $splPriorityQueueFactory
    ) {
    }

    /**
     * Get matched product meta rules ordered from lowest to highest priority.
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return \SplPriorityQueue
     */
    public function getRulesQueue(\Magento\Catalog\Model\Product $product): \SplPriorityQueue
    {
        $attributeCodes = [];
        foreach ($product->getAttributes() as $attribute) {
            $attributeCodes[(int)$attribute->getAttributeId()] = $attribute->getAttributeCode();
        }
        $product->setData('attribute_codes', $attributeCodes);

        /** @var \SplPriorityQueue $queue */
        $priorityQueue = $this->splPriorityQueueFactory->create();

        $rulesCollection = $this->getProductMetaRules($product);

        $maxCategoryLevel = 1;
        $maxSortOrder = 0;
        foreach ($rulesCollection->getItems() as $rule) {
            $maxCategoryLevel = max($maxCategoryLevel, (int)$rule->getData('category_level'));
            $maxSortOrder = max($maxSortOrder, (int)$rule->getSortOrder());
        }

        foreach ($rulesCollection->getItems() as $rule) {
            if ($ruleMatchScore = $this->getRuleMatchScore($rule, $product)) {
                $priorityQueue->insert($rule, $this->calculatePriority(
                    $rule,
                    $ruleMatchScore,
                    $maxCategoryLevel,
                    $maxSortOrder
                ));
            }
        }

        return $priorityQueue;
    }

    /**
     * Load the product meta rules.
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return \Inchoo\Seo\Model\ResourceModel\Meta\ProductRule\Collection
     */
    protected function getProductMetaRules(
        \Magento\Catalog\Model\Product $product
    ): \Inchoo\Seo\Model\ResourceModel\Meta\ProductRule\Collection {
        /** @var \Inchoo\Seo\Model\ResourceModel\Meta\ProductRule\Collection $collection */
        $collection = $this->metaRuleCollectionFactory->create();

        $collection->addFieldToFilter('status', ['eq' => 1]);
        $collection->addFieldToFilter('store_id', [
            'in' => [(int)$product->getStoreId(), \Magento\Store\Model\Store::DEFAULT_STORE_ID]
        ]);

        $collection->addFieldToFilter(['product_type_id', 'product_type_id'], [
            ['null' => true],
            ['eq' => $product->getTypeId()]
        ]);

        // from catalog_category_product_index tables
        $indexerCategoryIds = $this->productCategoryList->getCategoryIds(
            (int)$product->getId(),
            (int)$product->getStoreId()
        );

        if ($categoryIds = $indexerCategoryIds ?: $product->getCategoryIds()) {
            $collection->addFieldToFilter(['category_id', 'category_id'], [
                ['null' => true],
                ['in' => array_map('intval', $categoryIds)]
            ]);
        } else {
            $collection->addFieldToFilter('category_id', ['null' => true]);
        }

        $collection->joinCategoryLevel();

        return $collection;
    }

    /**
     * Get the match score for the rule.
     *
     * @param \Inchoo\Seo\Model\Meta\ProductRule $rule
     * @param \Magento\Catalog\Model\Product $product
     * @return int
     */
    protected function getRuleMatchScore(
        \Inchoo\Seo\Model\Meta\ProductRule $rule,
        \Magento\Catalog\Model\Product $product
    ): int {
        $score = 0;

        $isStoreMatch = (int)$rule->getStoreId() !== \Magento\Store\Model\Store::DEFAULT_STORE_ID;

        if ($ruleAttributeOptionsData = $rule->getAttributeOptionsData()) {
            $attributeCodes = $product->getData('attribute_codes') ?: [];

            foreach ($ruleAttributeOptionsData as $dataRow) {
                $dataRowAttributeCode = $attributeCodes[(int)$dataRow['attribute']] ?? '';
                $dataRowOptionId = (int)$dataRow['option'];

                if (!$dataRowAttributeCode || !$productOptionsIds = $product->getData($dataRowAttributeCode)) {
                    return 0; // attribute didn't match
                }

                $productOptionsIds = array_map('intval', explode(',', (string)$productOptionsIds));
                if (!in_array($dataRowOptionId, $productOptionsIds, true)) {
                    return 0; // attribute value didn't match
                }
            }

            $score += $isStoreMatch ? 1 : 2; // attribute match
        }

        if ($rule->getProductType()) {
            $score += $isStoreMatch ? 4 : 8; // product type match
        }

        if ($rule->getCategoryId()) {
            $score += $isStoreMatch ? 16 : 32; // category match
        }

        return $score;
    }

    /**
     * Calculate the meta rule priority.
     *
     * @param \Inchoo\Seo\Model\Meta\ProductRule $rule
     * @param int $ruleMatchScore
     * @param int $maxCategoryLevel
     * @param int $maxSortOrder
     * @return int
     */
    protected function calculatePriority(
        \Inchoo\Seo\Model\Meta\ProductRule $rule,
        int $ruleMatchScore,
        int $maxCategoryLevel,
        int $maxSortOrder
    ): int {
        $categoryLevel = (int)$rule->getData('category_level'); // a deeper level has higher priority
        $sortOrder = (int)$rule->getSortOrder(); // a lower number has higher priority

        $priority = match ($ruleMatchScore) {
            16 + 4 + 1 => 10 - $categoryLevel, // category + product type + attribute match (store)
            32 + 8 + 2 => 20 - $categoryLevel, // category + product type + attribute match (default store)
            16 + 4 => 30 - $categoryLevel, // category + product type match (store)
            32 + 8 => 40 - $categoryLevel, // category + product type match (default store)
            16 + 1 => 50 - $categoryLevel, // category + attribute match (store)
            32 + 2 => 60 - $categoryLevel, // category + attribute match (default store)
            16 => 70 - $categoryLevel, // category match (store)
            32 => 80 - $categoryLevel, // category match (default store)
            4 + 1 => 90, // product type + attribute match (store)
            8 + 2 => 100, // product type + attribute match (default store)
            4 => 110, // product type match (store)
            8 => 120, // product type match (default store)
            1 => 130, // attribute match (store)
            2 => 140, // attribute match (default store)
            default => 1000
        };

        $priority += $maxCategoryLevel - $maxSortOrder + $sortOrder;

        return $priority;
    }
}
