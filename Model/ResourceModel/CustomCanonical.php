<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\ResourceModel;

class CustomCanonical extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Resource initialization.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('inchoo_seo_custom_canonical', 'entity_id');
    }

    /**
     * Perform actions before object save.
     *
     * @param \Magento\Framework\Model\AbstractModel|\Inchoo\Seo\Model\CustomCanonical $object
     * @return self
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$object->getStoreId()) {
            $object->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID);
        }

        $object->setRequestPath(ltrim($object->getRequestPath(), '/') ?: '/');
        $object->setTargetPath(ltrim($object->getTargetPath(), '/') ?: '/');

        return $this;
    }

    /**
     * Get the custom canonical target path.
     *
     * @param string $requestPath
     * @param int $storeId
     * @return string
     */
    public function getTargetPath(string $requestPath, int $storeId): string
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), ['target_path']);
        $select->where('status = ?', 1);
        $select->where('store_id IN(?)', [$storeId, \Magento\Store\Model\Store::DEFAULT_STORE_ID]);
        $select->where('request_path = ?', $requestPath);
        $select->order('store_id DESC');
        $select->limit(1);

        return $this->getConnection()->fetchOne($select) ?: '';
    }
}
