<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Seo;

class Toolbar
{
    /**
     * @param \Magento\Catalog\Helper\Product\ProductList $productListHelper
     */
    public function __construct(
        protected \Magento\Catalog\Helper\Product\ProductList $productListHelper
    ) {
    }

    /**
     * Get default toolbar query parameters.
     *
     * @param array $excludeParams
     * @return array
     */
    public function getDefaultParamValues(array $excludeParams = []): array
    {
        $defaultSortField = $this->productListHelper->getDefaultSortField();
        $defaultSortDirection = \Magento\Catalog\Helper\Product\ProductList::DEFAULT_SORT_DIRECTION;
        $defaultViewMode = $this->productListHelper->getDefaultViewMode();
        $defaultLimitPerPage = $this->productListHelper->getDefaultLimitPerPageValue($defaultViewMode);

        $result = [
            \Magento\Catalog\Model\Product\ProductList\Toolbar::PAGE_PARM_NAME => 1,
            \Magento\Catalog\Model\Product\ProductList\Toolbar::ORDER_PARAM_NAME => $defaultSortField,
            \Magento\Catalog\Model\Product\ProductList\Toolbar::DIRECTION_PARAM_NAME => $defaultSortDirection,
            \Magento\Catalog\Model\Product\ProductList\Toolbar::MODE_PARAM_NAME => $defaultViewMode,
            \Magento\Catalog\Model\Product\ProductList\Toolbar::LIMIT_PARAM_NAME => $defaultLimitPerPage
        ];

        return array_diff_key($result, array_flip($excludeParams));
    }
}
