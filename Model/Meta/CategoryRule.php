<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Meta;

class CategoryRule extends \Magento\Framework\Model\AbstractModel implements
    \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'category_meta_rule';

    /**
     * Initialize model object.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule::class);
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
     * Get can be fallback.
     *
     * @return int|string|null
     */
    public function getCanBeFallback(): int|string|null
    {
        return $this->_getData('can_be_fallback');
    }

    /**
     * Can be fallback.
     *
     * @return bool
     */
    public function canBeFallback(): bool
    {
        return (bool)$this->getCanBeFallback();
    }

    /**
     * Set can be fallback.
     *
     * @param int|string|null $canBeFallback
     * @return self
     */
    public function setCanBeFallback(int|string|null $canBeFallback): self
    {
        return $this->setData('can_be_fallback', $canBeFallback);
    }

    /**
     * Get attribute IDs.
     *
     * @return string|null
     */
    public function getAttributeIds(): string|null
    {
        return $this->_getData('attribute_ids');
    }

    /**
     * Set attribute IDs.
     *
     * @param string|array|null $attributeIds
     * @return self
     */
    public function setAttributeIds(string|array|null $attributeIds): self
    {
        $attributeIds = $attributeIds ?: null;

        if (is_array($attributeIds)) {
            sort($attributeIds);
            $attributeIds = implode(',', $attributeIds);
        }

        return $this->setData('attribute_ids', $attributeIds);
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
        $this->setAttributeIds(array_column($this->getAttributeOptionsData(), 'attribute'));

        return $this;
    }

    /**
     * Get allow additional attributes.
     *
     * @return int|string|null
     */
    public function getAllowAdditionalAttributes(): int|string|null
    {
        return $this->_getData('allow_additional_attributes');
    }

    /**
     * Set allow additional attributes.
     *
     * @param int|string|null $allowAdditionAttributes
     * @return self
     */
    public function setAllowAdditionalAttributes(int|string|null $allowAdditionAttributes): self
    {
        return $this->setData('allow_additional_attributes', $allowAdditionAttributes);
    }

    /**
     * Get add to sitemap.
     *
     * @return int|string|null
     */
    public function getAddToSitemap(): int|string|null
    {
        return $this->_getData('add_to_sitemap');
    }

    /**
     * Set add to sitemap.
     *
     * @param int|string|null $addToSitemap
     * @return self
     */
    public function setAddToSitemap(int|string|null $addToSitemap): self
    {
        return $this->setData('add_to_sitemap', $addToSitemap);
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
     * Get canonical URL.
     *
     * @return string|null
     */
    public function getCanonical(): string|null
    {
        return $this->_getData('canonical');
    }

    /**
     * Set canonical URL.
     *
     * @param string|null $canonical
     * @return self
     */
    public function setCanonical(string|null $canonical): self
    {
        return $this->setData('canonical', $canonical);
    }

    /**
     * Get use attributes in canonical.
     *
     * @return int|string|null
     */
    public function getUseAttributesInCanonical(): int|string|null
    {
        return $this->_getData('canonical_use_attributes');
    }

    /**
     * Set use attributes in canonical.
     *
     * @param int|string|null $canonicalUseAttributes
     * @return self
     */
    public function setUseAttributesInCanonical(int|string|null $canonicalUseAttributes): self
    {
        return $this->setData('canonical_use_attributes', $canonicalUseAttributes);
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
     * Get description.
     *
     * @return string|null
     */
    public function getDescription(): string|null
    {
        return $this->_getData('description');
    }

    /**
     * Set description.
     *
     * @param string|null $description
     * @return self
     */
    public function setDescription(string|null $description): self
    {
        return $this->setData('description', $description);
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
        if ($categoryId = $this->getCategoryId()) {
            return [\Magento\Catalog\Model\Category::CACHE_TAG . '_' . $categoryId];
        }

        return [];
    }
}
