<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\ResourceModel\Meta;

class CustomRobots extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Resource initialization.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('inchoo_seo_custom_meta_robots', 'entity_id');
    }

    /**
     * Perform actions before object save.
     *
     * @param \Magento\Framework\Model\AbstractModel|\Inchoo\Seo\Model\Meta\CustomRobots $object
     * @return self
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$object->getStoreId()) {
            $object->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID);
        }

        $sortOrder = $object->getData('sort_order');
        if ($sortOrder === null || $sortOrder === '') {
            $object->setData('sort_order', $this->getMaxSortOrder() + 10);
        }

        return $this;
    }

    /**
     * Get the maximum sort order from the main table.
     *
     * @return int
     */
    public function getMaxSortOrder(): int
    {
        $connection = $this->getConnection();

        $select = $connection->select();
        $select->from($this->getMainTable(), 'MAX(sort_order)');

        return (int)$connection->fetchOne($select);
    }
}
