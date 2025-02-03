<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\ResourceModel;

class ProductCategoryList
{
    /**
     * @var array
     */
    protected array $categoryIdList = [];

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     * @param \Magento\Catalog\Model\Indexer\Category\Product\TableMaintainer $tableMaintainer
     */
    public function __construct(
        protected \Magento\Catalog\Model\ResourceModel\Product $productResource,
        protected \Magento\Catalog\Model\Indexer\Category\Product\TableMaintainer $tableMaintainer
    ) {
    }

    /**
     * Category ids from catalog_category_product_index tables.
     *
     * @param int $productId
     * @param int $storeId
     * @return int[]
     */
    public function getCategoryIds(int $productId, int $storeId): array
    {
        if (!isset($this->categoryIdList[$productId])) {
            $select = $this->getCategorySelect(
                $productId,
                $this->tableMaintainer->getMainTable($storeId)
            );

            $this->categoryIdList[$productId] = array_map(
                'intval',
                $this->productResource->getConnection()->fetchCol($select)
            );
        }

        return $this->categoryIdList[$productId];
    }

    /**
     * Create a new \Magento\Framework\DB\Select object.
     *
     * @param int $productId
     * @param string $tableName
     * @return \Magento\Framework\DB\Select
     */
    public function getCategorySelect(int $productId, string $tableName): \Magento\Framework\DB\Select
    {
        $select = $this->productResource->getConnection()->select();
        $select->from($tableName, ['category_id']);
        $select->where('product_id = ?', $productId);

        return $select;
    }
}
