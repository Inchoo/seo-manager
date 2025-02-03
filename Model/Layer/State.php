<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Layer;

/**
 * Wrapper around:
 * @see \Magento\Catalog\Model\Layer\State
 */
class State
{
    /**
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Config $config,
        protected \Magento\Catalog\Model\Layer\Resolver $layerResolver,
    ) {
    }

    /**
     * Get the applied layer filter items.
     *
     * @return \Magento\Catalog\Model\Layer\Filter\Item[]
     */
    public function getAllFilterItems(): array
    {
        $layer = $this->layerResolver->get();
        return $layer->getState()->getFilters();
    }

    /**
     * Get the filter items permitted for meta rules.
     *
     * @return \Magento\Catalog\Model\Layer\Filter\Item[]
     */
    public function getMetaFilterItems(): array
    {
        $excludedAttributeIds = $this->config->getExcludedAttributeIds();

        $filterItems = [];
        foreach ($this->getAllFilterItems() as $filterItem) {
            try {
                $attributeId = $filterItem->getFilter()->getAttributeModel()->getAttributeId();
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                continue; // no attribute model means that this is not an attribute filter
            }

            if (!in_array((int)$attributeId, $excludedAttributeIds, true)) {
                $filterItems[] = $filterItem;
            }
        }

        return $filterItems;
    }

    /**
     * Get the attribute options of filter items permitted for meta rules.
     *
     * @return array
     */
    public function getFilterAttributeOptions(): array
    {
        $result = [];
        foreach ($this->getMetaFilterItems() as $filterItem) {
            $attribute = $filterItem->getFilter()->getAttributeModel();
            $source = $attribute->getSource();

            $itemValue = $filterItem->getValue();
            $values = is_array($itemValue) ? $itemValue : [$itemValue];

            foreach ($values as $value) {
                $result[(int)$attribute->getAttributeId()][] = (int)$source->getOptionId($value);
            }
        }

        return array_map('array_unique', $result);
    }
}
