<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Meta;

class CustomRobots extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'custom_meta_robots';

    /**
     * Initialize model object.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Inchoo\Seo\Model\ResourceModel\Meta\CustomRobots::class);
    }

    /**
     * Get entity ID.
     *
     * @return int|string|null
     */
    public function getId()
    {
        return $this->getEntityId();
    }

    /**
     * Set entity ID.
     *
     * @param int|string|null $value
     * @return self
     */
    public function setId($value): self
    {
        return $this->setEntityId($value);
    }

    /**
     * Get entity ID.
     *
     * @return int|string|null
     */
    public function getEntityId()
    {
        return $this->_getData('entity_id');
    }

    /**
     * Set entity ID.
     *
     * @param int|string|null $entityId
     * @return self
     */
    public function setEntityId($entityId)
    {
        return $this->setData('entity_id', $entityId);
    }

    /**
     * Get status.
     *
     * @return int|string|null mixed
     */
    public function getStatus(): int|string|null
    {
        return $this->_getData('status');
    }

    /**
     * Set status.
     *
     * @param int|string|null $status
     * @return self
     */
    public function setStatus(int|string|null $status): self
    {
        return $this->setData('status', $status);
    }

    /**
     * Get store ID.
     *
     * @return int|string|null
     */
    public function getStoreId(): int|string|null
    {
        return $this->_getData('store_id');
    }

    /**
     * Set store ID.
     *
     * @param int|string|null $storeId
     * @return self
     */
    public function setStoreId(int|string|null $storeId): self
    {
        return $this->setData('store_id', $storeId);
    }

    /**
     * Get URL path.
     *
     * @return string|null
     */
    public function getUrlPath(): string|null
    {
        return $this->_getData('url_path');
    }

    /**
     * Set URL path.
     *
     * @param string|null $urlPath
     * @return self
     */
    public function setUrlPath(string|null $urlPath): self
    {
        return $this->setData('url_path', $urlPath);
    }

    /**
     * Get meta robots.
     *
     * @return string|null
     */
    public function getMetaRobots(): string|null
    {
        return $this->_getData('meta_robots');
    }

    /**
     * Set meta robots.
     *
     * @param string|null $metaRobots
     * @return self
     */
    public function setMetaRobots(string|null $metaRobots): self
    {
        return $this->setData('meta_robots', $metaRobots);
    }

    /**
     * Get sort order.
     *
     * @return int|string|null
     */
    public function getSortOrder(): int|string|null
    {
        return $this->_getData('sort_order');
    }

    /**
     * Set sort order.
     *
     * @param int|string|null $sortOrder
     * @return self
     */
    public function setSortOrder(int|string|null $sortOrder): self
    {
        return $this->setData('sort_order', $sortOrder);
    }

    /**
     * Get created at.
     *
     * @return string|null
     */
    public function getCreatedAt(): string|null
    {
        return $this->_getData('created_at');
    }

    /**
     * Get updated at.
     *
     * @return string|null
     */
    public function getUpdatedAt(): string|null
    {
        return $this->_getData('updated_at');
    }
}
