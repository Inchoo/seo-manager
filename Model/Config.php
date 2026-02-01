<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model;

class Config
{
    public const XML_PATH_IS_ENABLED                             = 'catalog/inchoo_seo/active';
    public const XML_PATH_IS_ENABLED_CMS_PAGE_CANONICAL_TAG      = 'catalog/inchoo_seo/cms_page_canonical_tag';
    public const XML_PATH_IS_ENABLED_FILTER_CANONICAL_LINK       = 'catalog/inchoo_seo/filter_canonical_link';
    public const XML_PATH_ALLOWED_CANONICAL_LINK_QUERY_STRINGS   = 'catalog/inchoo_seo/allowed_canonical_link_query_strings'; // phpcs:ignore
    public const XML_PATH_CATALOG_SEARCH_META_ROBOTS             = 'catalog/inchoo_seo/catalog_search_meta_robots';
    public const XML_PATH_NON_DEFAULT_TOOLBAR_META_ROBOTS        = 'catalog/inchoo_seo/non_default_toolbar_meta_robots';
    public const XML_PATH_IS_ENABLED_CATEGORY_META_RULES         = 'catalog/inchoo_seo/category_meta_rules';
    public const XML_PATH_IS_ENABLED_CATEGORY_META_RULES_SITEMAP = 'catalog/inchoo_seo/category_meta_rules_sitemap';
    public const XML_PATH_IS_ENABLED_PRODUCT_META_RULES          = 'catalog/inchoo_seo/product_meta_rules';
    public const XML_PATH_EXCLUDED_ATTRIBUTES                    = 'catalog/inchoo_seo/excluded_attributes';

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Is extension enabled.
     *
     * @param int|string|null $scopeCode
     * @return bool
     */
    public function isEnabled(int|string|null $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            static::XML_PATH_IS_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * Use Canonical Link Meta Tag For Categories.
     *
     * @param int|string|null $scopeCode
     * @return bool
     */
    public function useCanonicalLinkMetaTagForCategories(int|string|null $scopeCode = null): bool
    {
        return $this->isEnabled() && $this->scopeConfig->isSetFlag(
            \Magento\Catalog\Helper\Category::XML_PATH_USE_CATEGORY_CANONICAL_TAG,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * Use Canonical Link Meta Tag For Products.
     *
     * @param int|string|null $scopeCode
     * @return bool
     */
    public function useCanonicalLinkMetaTagForProducts(int|string|null $scopeCode = null): bool
    {
        return $this->isEnabled() && $this->scopeConfig->isSetFlag(
            \Magento\Catalog\Helper\Product::XML_PATH_USE_PRODUCT_CANONICAL_TAG,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * Use Canonical Link Meta Tag For Cms Pages.
     *
     * @param int|string|null $scopeCode
     * @return bool
     */
    public function useCanonicalLinkMetaTagForCmsPages(int|string|null $scopeCode = null): bool
    {
        return $this->isEnabled() && $this->scopeConfig->isSetFlag(
            static::XML_PATH_IS_ENABLED_CMS_PAGE_CANONICAL_TAG,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * CMS Home Page.
     *
     * @param int|string|null $scopeCode
     * @return string
     */
    public function getCmsHomePageIdentifier(int|string|null $scopeCode = null): string
    {
        return (string)$this->scopeConfig->getValue(
            \Magento\Cms\Helper\Page::XML_PATH_HOME_PAGE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * Filter Canonical Link Query String.
     *
     * @param int|string|null $scopeCode
     * @return bool
     */
    public function isEnabledCanonicalLinkFilter(int|string|null $scopeCode = null): bool
    {
        return $this->isEnabled() && $this->scopeConfig->isSetFlag(
            static::XML_PATH_IS_ENABLED_FILTER_CANONICAL_LINK,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * Allowed Canonical Link Query Strings.
     *
     * @param int|string|null $scopeCode
     * @return array
     */
    public function getAllowedCanonicalQueryStrings(int|string|null $scopeCode = null): array
    {
        $value = $this->scopeConfig->getValue(
            static::XML_PATH_ALLOWED_CANONICAL_LINK_QUERY_STRINGS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $scopeCode
        );

        return $value ? explode(',', $value) : [];
    }

    /**
     * Catalog Search Meta Robots.
     *
     * @param int|string|null $scopeCode
     * @return string
     */
    public function getCatalogSearchMetaRobots(int|string|null $scopeCode = null): string
    {
        return (string)$this->scopeConfig->getValue(
            static::XML_PATH_CATALOG_SEARCH_META_ROBOTS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * Non-Default Toolbar Meta Robots.
     *
     * @param int|string|null $scopeCode
     * @return string
     */
    public function getNonDefaultToolbarMetaRobots(int|string|null $scopeCode = null): string
    {
        return (string)$this->scopeConfig->getValue(
            static::XML_PATH_NON_DEFAULT_TOOLBAR_META_ROBOTS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * Category Meta Rules.
     *
     * @param int|string|null $scopeCode
     * @return bool
     */
    public function isEnabledCategoryMetaRules(int|string|null $scopeCode = null): bool
    {
        return $this->isEnabled() && $this->scopeConfig->isSetFlag(
            static::XML_PATH_IS_ENABLED_CATEGORY_META_RULES,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * Category Meta Rules Sitemap.
     *
     * @param int|string|null $scopeCode
     * @return bool
     */
    public function isEnabledCategoryMetaRulesSitemap(int|string|null $scopeCode = null): bool
    {
        return $this->isEnabledCategoryMetaRules() && $this->scopeConfig->isSetFlag(
            static::XML_PATH_IS_ENABLED_CATEGORY_META_RULES_SITEMAP,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * Excluded Attributes.
     *
     * @param int|string|null $scopeCode
     * @return int[]
     */
    public function getExcludedAttributeIds(int|string|null $scopeCode = null): array
    {
        $value = $this->scopeConfig->getValue(
            static::XML_PATH_EXCLUDED_ATTRIBUTES,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $scopeCode
        );

        if ($value) {
            $attributeIds = explode(',', $value);
            sort($attributeIds);
            return array_map('intval', $attributeIds);
        }

        return [];
    }

    /**
     * Product Meta Rules.
     *
     * @param int|string|null $scopeCode
     * @return bool
     */
    public function isEnabledProductMetaRules(int|string|null $scopeCode = null): bool
    {
        return $this->isEnabled() && $this->scopeConfig->isSetFlag(
            static::XML_PATH_IS_ENABLED_PRODUCT_META_RULES,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }
}
