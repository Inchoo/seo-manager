<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model;

class CustomCanonical extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'custom_canonical';

    /**
     * Initialize model object.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Inchoo\Seo\Model\ResourceModel\CustomCanonical::class);
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
     * @return int|string|null
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
     * Get request path.
     *
     * @return string|null
     */
    public function getRequestPath(): string|null
    {
        return $this->_getData('request_path');
    }

    /**
     * Set request path.
     *
     * @param string|null $requestPath
     * @return self
     */
    public function setRequestPath(string|null $requestPath): self
    {
        return $this->setData('request_path', $requestPath);
    }

    /**
     * Get request path.
     *
     * @return string|null
     */
    public function getTargetPath(): string|null
    {
        return $this->_getData('target_path');
    }

    /**
     * Set request path.
     *
     * @param string|null $targetPath
     * @return self
     */
    public function setTargetPath(string|null $targetPath): self
    {
        return $this->setData('target_path', $targetPath);
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
