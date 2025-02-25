<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\ResourceModel\Meta\ProductRule;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize collection.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Inchoo\Seo\Model\Meta\ProductRule::class,
            \Inchoo\Seo\Model\ResourceModel\Meta\ProductRule::class
        );
        $this->_setIdFieldName('entity_id');
    }

    /**
     * Join the category level column.
     *
     * @return void
     */
    public function joinCategoryLevel(): void
    {
        if (!$this->getFlag('is_category_level_joined')) {
            $this->getSelect()->joinLeft(
                ['category_level' => $this->getTable('catalog_category_entity')],
                'main_table.category_id = category_level.entity_id',
                ['level']
            );

            $this->setFlag('is_category_level_joined');
        }
    }
}
