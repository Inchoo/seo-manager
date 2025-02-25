<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Meta;

class ProductRule extends \Magento\Framework\Model\AbstractModel implements
    \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'product_meta_rule';

    /**
     * Initialize model object.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Inchoo\Seo\Model\ResourceModel\Meta\ProductRule::class);
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
    public function setEntityId($entityId): self
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
     * Get category ID.
     *
     * @return int|string|null
     */
    public function getCategoryId(): int|string|null
    {
        return $this->_getData('category_id');
    }

    /**
     * Set category ID.
     *
     * @param int|string|null $categoryId
     * @return self
     */
    public function setCategoryId(int|string|null $categoryId): self
    {
        return $this->setData('category_id', $categoryId);
    }

    /**
     * Get product type.
     *
     * @return string|null
     */
    public function getProductType(): string|null
    {
        return $this->_getData('product_type_id');
    }

    /**
     * Set product type.
     *
     * @param string|null $productType
     * @return self
     */
    public function setProductType(string|null $productType): self
    {
        return $this->setData('product_type_id', $productType);
    }

    /**
     * Get attribute options.
     *
     * @return string|null
     */
    public function getAttributeOptions(): string|null
    {
        return $this->_getData('attribute_options');
    }

    /**
     * Get attribute options data.
     *
     * @return array
     * @throws \JsonException
     */
    public function getAttributeOptionsData(): array
    {
        $attributeOptions = $this->getAttributeOptions();
        return $attributeOptions ? json_decode($attributeOptions, true, 512, JSON_THROW_ON_ERROR) : [];
    }

    /**
     * Set attribute options.
     *
     * @param string|array|null $attributeOptions
     * @return self
     * @throws \JsonException
     */
    public function setAttributeOptions(string|array|null $attributeOptions): self
    {
        $attributeOptions = $attributeOptions ?: null;

        if (is_array($attributeOptions)) {
            $attributeOptions = json_encode($attributeOptions, JSON_THROW_ON_ERROR);
        }

        $this->setData('attribute_options', $attributeOptions);

        return $this;
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
     * Get meta title.
     *
     * @return string|null
     */
    public function getMetaTitle(): string|null
    {
        return $this->_getData('meta_title');
    }

    /**
     * Set meta title.
     *
     * @param string|null $metaTitle
     * @return self
     */
    public function setMetaTitle(string|null $metaTitle): self
    {
        return $this->setData('meta_title', $metaTitle);
    }

    /**
     * Get meta keywords.
     *
     * @return string|null
     */
    public function getMetaKeywords(): string|null
    {
        return $this->_getData('meta_keywords');
    }

    /**
     * Set meta keywords.
     *
     * @param string|null $metaKeywords
     * @return self
     */
    public function setMetaKeywords(string|null $metaKeywords): self
    {
        return $this->setData('meta_keywords', $metaKeywords);
    }

    /**
     * Get meta description.
     *
     * @return string|null
     */
    public function getMetaDescription(): string|null
    {
        return $this->_getData('meta_description');
    }

    /**
     * Set meta description.
     *
     * @param string|null $metaDescription
     * @return self
     */
    public function setMetaDescription(string|null $metaDescription): self
    {
        return $this->setData('meta_description', $metaDescription);
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
     * Get H1 title.
     *
     * @return string|null
     */
    public function getH1Title(): string|null
    {
        return $this->_getData('h1_title');
    }

    /**
     * Set H1 title.
     *
     * @param string|null $h1Title
     * @return self
     */
    public function setH1Title(string|null $h1Title): self
    {
        return $this->setData('h1_title', $h1Title);
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

    /**
     * Get cache identities.
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return [\Magento\Catalog\Model\Product::CACHE_TAG];
    }
}
