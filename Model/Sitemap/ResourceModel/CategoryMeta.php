<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Sitemap\ResourceModel;

class CategoryMeta
{
    /**
     * @param \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule\CollectionFactory $collectionFactory
     * @param \Magento\Store\Model\App\Emulation $appEmulation
     * @param \Magento\UrlRewrite\Model\UrlFinderInterface $urlFinder
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\ObjectManager\ConfigLoaderInterface $configLoader
     */
    public function __construct(
        protected \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule\CollectionFactory $collectionFactory,
        protected \Magento\Store\Model\App\Emulation $appEmulation,
        protected \Magento\UrlRewrite\Model\UrlFinderInterface $urlFinder,
        protected \Magento\Framework\ObjectManagerInterface $objectManager,
        protected \Magento\Framework\ObjectManager\ConfigLoaderInterface $configLoader
    ) {
    }

    /**
     * Get category meta rules data.
     *
     * @param int $storeId
     * @return array
     */
    public function getData(int $storeId): array
    {
        /** @var \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', 1);
        $collection->addFieldToFilter('add_to_sitemap', 1);
        $collection->addFieldToFilter('category_id', ['notnull' => true]);
        $collection->addFieldToFilter('attribute_options', ['notnull' => true]);
        $collection->addFieldToFilter('store_id', ['in' => [$storeId, \Magento\Store\Model\Store::DEFAULT_STORE_ID]]);

        $categoryAttributeData = [];

        /** @var \Inchoo\Seo\Model\Meta\CategoryRule $metaRule */
        foreach ($collection->getItems() as $metaRule) {
            try {
                $attributeOptionsData = $metaRule->getAttributeOptionsData();
            } catch (\JsonException $e) {
                continue;
            }

            if (count($attributeOptionsData) > 1) {
                continue;
            }

            $categoryId = (int)$metaRule->getCategoryid();
            foreach ($attributeOptionsData as $dataRow) {
                $categoryAttributeData[$categoryId][(int)$dataRow['attribute']][] = (int)$dataRow['option'];
            }
        }

        $this->appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND);
        $this->objectManager->configure($this->configLoader->load(\Magento\Framework\App\Area::AREA_FRONTEND));

        try {
            $urls = $this->getUrls($categoryAttributeData, $storeId);
        } catch (\Exception $e) {
            $urls = [];
        } finally {
            $this->appEmulation->stopEnvironmentEmulation();
        }

        return array_map(static function ($url) {
            return ['url' => $url];
        }, $urls);
    }

    /**
     * Get URLs.
     *
     * @param array $categoryAttributeData
     * @param int $storeId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getUrls(array $categoryAttributeData, int $storeId): array
    {
        $urls = [];

        foreach ($categoryAttributeData as $categoryId => $attributeOptions) {
            $layerFilters = $this->getLayerFilters($categoryId);
            $categoryUrl = $this->getCategoryUrl($categoryId, $storeId);

            foreach ($attributeOptions as $attributeId => $optionIds) {
                $filter = null;
                foreach ($layerFilters as $layerFilter) {
                    if ((int)$layerFilter->getAttributeModel()->getAttributeId() === $attributeId) {
                        $filter = $layerFilter;
                        break;
                    }
                }

                if (!$filter) {
                    continue;
                }

                $isWildcardAttribute = in_array(0, $optionIds, true);

                /** @var \Magento\Catalog\Model\Layer\Filter\Item $item */
                foreach ($filter->getItems() as $item) {
                    $optionId = $filter->getAttributeModel()->getSource()->getOptionId($item->getValue());
                    $isAllowedItem = $optionId && in_array((int)$optionId, $optionIds, true);

                    if ($isWildcardAttribute || $isAllowedItem) {
                        $urls[] = "{$categoryUrl}?{$item->getFilter()->getRequestVar()}={$item->getValue()}";
                    }
                }
            }
        }

        return $urls;
    }

    /**
     * Get current layered navigation filters.
     *
     * @param int $categoryId
     * @return \Magento\Catalog\Model\Layer\Filter\AbstractFilter[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getLayerFilters(int $categoryId): array
    {
        $request = $this->objectManager->create(\Magento\Framework\App\RequestInterface::class);
        $request->setParams(['id' => $categoryId]);

        $layer = $this->objectManager->create(\Magento\Catalog\Model\Layer\Category::class);
        $layer->setCurrentCategory($categoryId);

        /** @var \Magento\Catalog\Model\Layer\FilterList $filterList */
        $filterList = $this->objectManager->create('categoryFilterList');

        $filters = [];
        foreach ($filterList->getFilters($layer) as $filter) {
            $filter->apply($request);

            if ($filter->getData('attribute_model')) {
                $filters[] = $filter;
            }
        }

        $layer->apply();

        return $filters;
    }

    /**
     * Get category URL.
     *
     * @param int $categoryId
     * @param int $storeId
     * @return string
     */
    protected function getCategoryUrl(int $categoryId, int $storeId): string
    {
        $rewriteEntityType = \Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator::ENTITY_TYPE;

        $rewrite = $this->urlFinder->findOneByData(
            [
                \Magento\UrlRewrite\Service\V1\Data\UrlRewrite::ENTITY_ID => $categoryId,
                \Magento\UrlRewrite\Service\V1\Data\UrlRewrite::ENTITY_TYPE => $rewriteEntityType,
                \Magento\UrlRewrite\Service\V1\Data\UrlRewrite::IS_AUTOGENERATED => 1,
                \Magento\UrlRewrite\Service\V1\Data\UrlRewrite::STORE_ID => $storeId,
                \Magento\UrlRewrite\Service\V1\Data\UrlRewrite::REDIRECT_TYPE => 0
            ]
        );

        return $rewrite ? $rewrite->getRequestPath() : "catalog/category/view/id/{$categoryId}";
    }
}
