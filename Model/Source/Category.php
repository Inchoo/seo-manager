<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Source;

class Category implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var array
     */
    private array $options = [];

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory
     */
    public function __construct(
        protected \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory
    ) {
    }

    /**
     * Return category options as value-label pairs.
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $categoriesByParent = [];
            foreach ($this->getCollection() as $category) {
                $categoriesByParent[$category->getParentId()][] = $category;
            }

            $this->options = $this->generateOptions(
                $this->generateTree($categoriesByParent)
            );
        }

        return $this->options;
    }

    /**
     * Get the category collection.
     *
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    protected function getCollection(): \Magento\Catalog\Model\ResourceModel\Category\Collection
    {
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect(['name']);
        $collection->setOrder(['level', 'position']);

        return $collection;
    }

    /**
     * Generate the category tree.
     *
     * @param \Magento\Catalog\Model\Category[][] $categoriesByParent
     * @param int $rootId
     * @return \Magento\Catalog\Model\Category[]
     */
    protected function generateTree(
        array $categoriesByParent,
        int $rootId = \Magento\Catalog\Model\Category::TREE_ROOT_ID
    ): array {
        $tree = [];
        foreach ($categoriesByParent[$rootId] ?? [] as $childCategory) {
            $childCategory->setChildrenData(
                $this->generateTree($categoriesByParent, (int)$childCategory->getId())
            );
            $tree[] = $childCategory;
        }

        return $tree;
    }

    /**
     *  Output:
     *      PARENT
     *      PARENT / CHILD
     *      PARENT / CHILD / GRANDCHILD
     *      PARENT / CHILD
     *      PARENT / CHILD / GRANDCHILD
     *      PARENT / CHILD / GRANDCHILD / ...
     *
     * @param \Magento\Catalog\Model\Category[] $categoryTree
     * @return array
     */
    protected function generateOptions(array $categoryTree): array
    {
        $options = [];
        foreach ($categoryTree as $category) {
            $options[] = [
                'value' => $category->getId(),
                'label' => $category->getName()
            ];

            if ($childCategories = $category->getChildrenData()) {
                foreach ($this->generateOptions($childCategories) as $childOption) {
                    // add parent's name before child's name
                    $childOption['label'] = "{$category->getName()} / {$childOption['label']}";
                    $options[] = $childOption;
                }
            }
        }

        return $options;
    }
}
