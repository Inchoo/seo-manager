<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Meta\Fallback;

class CategoryRuleQueue
{
    /**
     * @param \Inchoo\Seo\Model\Meta\CategoryRuleRepository $categoryMetaRuleRepository
     * @param \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule\CollectionFactory $metaRuleCollectionFactory
     * @param \SplPriorityQueueFactory $splPriorityQueueFactory
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Meta\CategoryRuleRepository $categoryMetaRuleRepository,
        protected \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule\CollectionFactory $metaRuleCollectionFactory,
        protected \SplPriorityQueueFactory $splPriorityQueueFactory
    ) {
    }

    /**
     * Get matched category meta rules ordered from lowest to highest priority.
     *
     * @param \Magento\Catalog\Model\Category $category
     * @param array $filterAttributeOptions
     * @return \SplPriorityQueue
     */
    public function getRulesQueue(
        \Magento\Catalog\Model\Category $category,
        array $filterAttributeOptions
    ): \SplPriorityQueue {
        /** @var \SplPriorityQueue $queue */
        $priorityQueue = $this->splPriorityQueueFactory->create();

        $rulesCollection = $this->getCategoryMetaRules($category, array_keys($filterAttributeOptions));
        foreach ($rulesCollection->getItems() as $rule) {
            if ($ruleMatchScore = $this->getRuleMatchScore($rule, $category, $filterAttributeOptions)) {
                $priorityQueue->insert($rule, $this->calculatePriority($rule, $category, $ruleMatchScore));
            }
        }

        return $priorityQueue;
    }

    /**
     * Load the category meta rules.
     *
     * @param \Magento\Catalog\Model\Category $category
     * @param array $attributeIds
     * @return \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule\Collection
     */
    protected function getCategoryMetaRules(
        \Magento\Catalog\Model\Category $category,
        array $attributeIds
    ): \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule\Collection {
        $collection = $this->metaRuleCollectionFactory->create();

        $collection->addFieldToFilter('status', ['eq' => 1]);
        $collection->addFieldToFilter('store_id', [
            'in' => [(int)$category->getStoreId(), \Magento\Store\Model\Store::DEFAULT_STORE_ID]
        ]);

        $collection->addFieldToFilter(['category_id', 'category_id'], [
            ['null' => true],
            ['in' => array_map('intval', $category->getPathIds())]
        ]);

        if ($attributeIds) {
            sort($attributeIds);
            $collection->addFieldToFilter(['attribute_ids', 'attribute_ids', 'allow_additional_attributes'], [
                ['null' => true],
                ['eq' => implode(',', $attributeIds)],
                ['eq' => 1]
            ]);
        } else {
            $collection->addFieldToFilter('attribute_ids', ['null' => true]);
        }

        return $collection;
    }

    /**
     * Get the match score for the rule.
     *
     * @param \Inchoo\Seo\Model\Meta\CategoryRule $rule
     * @param \Magento\Catalog\Model\Category $category
     * @param array $filterAttributeOptions
     * @return int
     * @throws \JsonException
     */
    protected function getRuleMatchScore(
        \Inchoo\Seo\Model\Meta\CategoryRule $rule,
        \Magento\Catalog\Model\Category $category,
        array $filterAttributeOptions
    ): int {
        $score = 0;

        $isStoreMatch = (int)$rule->getStoreId() !== \Magento\Store\Model\Store::DEFAULT_STORE_ID;

        if ($ruleAttributeOptionsData = $rule->getAttributeOptionsData()) {
            $ruleAttributeOptions = $wildcardAttributeIds = [];

            // parse rule attribute options data
            foreach ($ruleAttributeOptionsData as $dataRow) {
                $dataRowAttributeId = (int)$dataRow['attribute'];
                $dataRowOptionId = (int)$dataRow['option'];

                $ruleAttributeOptions[$dataRowAttributeId][] = $dataRowOptionId;

                if ($dataRowOptionId === 0) {
                    $wildcardAttributeIds[] = $dataRowAttributeId;
                }
            }

            // check if the rule options match the layered navigation filter options
            foreach ($ruleAttributeOptions as $ruleAttributeId => $ruleOptionIds) {
                // check if the rule attribute is used in the layered navigation filter
                if (!$filterOptionIds = $filterAttributeOptions[$ruleAttributeId] ?? []) {
                    return 0; // attribute didn't match
                }

                // check whether rule options are being used in the layered navigation filter
                foreach ($ruleOptionIds as $ruleOptionId) {
                    if ($ruleOptionId !== 0 && !in_array($ruleOptionId, $filterOptionIds, true)) {
                        return 0; // attribute option didn't match
                    }
                }

                // check if all layered navigation filter options match the rule options (without wildcard)
                if (!in_array($ruleAttributeId, $wildcardAttributeIds, true)
                    && array_diff($filterOptionIds, $ruleOptionIds)
                ) {
                    return 0; // attribute didn't match
                }
            }

            if ($wildcardAttributeIds) {
                $score += $isStoreMatch ? 4 : 8; // attribute match with wildcard
            } else {
                $score += $isStoreMatch ? 1 : 2; // attribute match without wildcard
            }

            $additionalFilterAttributes = array_diff(
                array_keys($filterAttributeOptions), // filter attribute ids
                array_keys($ruleAttributeOptions) // rule attribute ids
            );
            if ($additionalFilterAttributes) {
                $score += $isStoreMatch ? 256 : 512; // attribute match with additional attributes
            }
        }

        $ruleCategoryId = $rule->getCategoryId() ? (int)$rule->getCategoryId() : null;
        if ($ruleCategoryId) {
            $parentIds = array_map('intval', $category->getParentIds());

            if ($ruleCategoryId === (int)$category->getId()) {
                $score += $isStoreMatch ? 16 : 32; // category match
            } elseif ($rule->canBeFallback() && in_array($ruleCategoryId, $parentIds, true)) {
                $score += $isStoreMatch ? 64 : 128; // parent category match
            } else {
                return 0; // category didn't match
            }
        }

        return $score;
    }

    /**
     * Calculate the meta rule priority.
     *
     * @param \Inchoo\Seo\Model\Meta\CategoryRule $rule
     * @param \Magento\Catalog\Model\Category $category
     * @param int $ruleMatchScore
     * @return int
     */
    protected function calculatePriority(
        \Inchoo\Seo\Model\Meta\CategoryRule $rule,
        \Magento\Catalog\Model\Category $category,
        int $ruleMatchScore
    ): int {
        $parentIds = array_map('intval', $category->getParentIds());
        $parentsCount = count($parentIds);

        $ruleCategoryId = $rule->getCategoryId() ? (int)$rule->getCategoryId() : null;
        $parentKey = array_search($ruleCategoryId, $parentIds, true) ?: 0;
        $parentPriority = $parentsCount - $parentKey;

        return match ($ruleMatchScore) {
            1 + 16        => 10, // attribute without wildcard + category (store)
            2 + 32        => 20, // attribute without wildcard + category (default store)
            4 + 16        => 30, // attribute with wildcard + category (store)
            8 + 32        => 40, // attribute with wildcard + category (default store)
            1 + 16 + 256  => 50, // attribute without wildcard + category + additional attributes (store)
            2 + 32 + 512  => 60, // attribute without wildcard + category + additional attributes (default store)
            4 + 16 + 256  => 70, // attribute with wildcard + category + additional attributes (store)
            8 + 32 + 512  => 80, // attribute with wildcard + category + additional attributes (default store)
            16            => 90, // category (store)
            32            => 100, // category (default store)
            1             => 110, // attribute without wildcard (store)
            2             => 120, // attribute without wildcard (default store)
            4             => 130, // attribute with wildcard (store)
            8             => 140, // attribute with wildcard (default store)
            1 + 256       => 150, // attribute without wildcard + additional attributes (store)
            2 + 512       => 160, // attribute without wildcard + additional attributes (default store)
            4 + 256       => 170, // attribute with wildcard + additional attributes (store)
            8 + 512       => 180, // attribute with wildcard + additional attributes (default store)
            1 + 64        => 190 + $parentPriority, // attribute without wildcard + parent category (store)
            2 + 128       => 200 + $parentPriority, // attribute without wildcard + parent category (default store)
            4 + 64        => 210 + $parentPriority, // attribute with wildcard + parent category (store)
            8 + 128       => 220 + $parentPriority, // attribute with wildcard + parent category (default store)

            // attribute without wildcard + parent category + additional attributes (store)
            1 + 64 + 256  => 230 + $parentPriority,
            // attribute without wildcard + parent category + additional attributes (default store)
            2 + 128 + 512 => 240 + $parentPriority,
            // attribute with wildcard + parent category + additional attributes (store)
            4 + 64 + 256  => 250 + $parentPriority,
            // attribute with wildcard + parent category + additional attributes (default store)
            8 + 128 + 512 => 260 + $parentPriority,

            64            => 270 + $parentPriority, // parent category (store)
            128           => 280 + $parentPriority, // parent category (default store)
            default       => 1000 + $parentsCount
        };
    }
}
