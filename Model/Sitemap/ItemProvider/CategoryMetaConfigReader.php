<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Sitemap\ItemProvider;

class CategoryMetaConfigReader implements \Magento\Sitemap\Model\ItemProvider\ConfigReaderInterface
{
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Get priority
     *
     * @param int $storeId
     * @return string
     */
    public function getPriority($storeId)
    {
        return (string)$this->scopeConfig->getValue(
            'sitemap/category_meta/priority',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get change frequency.
     *
     * @param int $storeId
     * @return string
     */
    public function getChangeFrequency($storeId)
    {
        return (string)$this->scopeConfig->getValue(
            'sitemap/category_meta/changefreq',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
