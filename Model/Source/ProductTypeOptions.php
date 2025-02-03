<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Source;

class ProductTypeOptions implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     */
    public function __construct(
        protected \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
    ) {
        $this->productTypeConfig = $productTypeConfig;
    }

    /**
     * Return product type options as value-label pairs.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getProductTypes();
    }

    /**
     * Get all product types.
     *
     * @return array
     */
    protected function getProductTypes(): array
    {
        $types = $this->productTypeConfig->getAll();
        $productTypes = [];

        foreach ($types as $type) {
            $productTypes[] = [
                'label' => $type['label'],
                'value' => $type['name']
            ];
        }

        return $productTypes;
    }
}
