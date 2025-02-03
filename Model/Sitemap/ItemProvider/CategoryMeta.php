<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Sitemap\ItemProvider;

class CategoryMeta implements \Magento\Sitemap\Model\ItemProvider\ItemProviderInterface
{
    /**
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Inchoo\Seo\Model\Sitemap\ResourceModel\CategoryMeta $categoryMetaResource
     * @param \Magento\Sitemap\Model\ItemProvider\ConfigReaderInterface $configReader
     * @param \Magento\Sitemap\Model\SitemapItemInterfaceFactory $itemFactory
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Config $config,
        protected \Inchoo\Seo\Model\Sitemap\ResourceModel\CategoryMeta $categoryMetaResource,
        protected \Magento\Sitemap\Model\ItemProvider\ConfigReaderInterface $configReader,
        protected \Magento\Sitemap\Model\SitemapItemInterfaceFactory $itemFactory
    ) {
    }

    /**
     * Get sitemap items.
     *
     * @param int $storeId
     * @return \Magento\Sitemap\Model\SitemapItemInterface[]
     */
    public function getItems($storeId)
    {
        if (!$this->config->isEnabledCategoryMetaRulesSitemap($storeId)) {
            return [];
        }

        $data = $this->categoryMetaResource->getData((int)$storeId);

        return array_map(function ($item) use ($storeId) {
            $item['priority'] = $this->configReader->getPriority($storeId);
            $item['changeFrequency'] = $this->configReader->getChangeFrequency($storeId);
            return $this->itemFactory->create($item);
        }, $data);
    }
}
