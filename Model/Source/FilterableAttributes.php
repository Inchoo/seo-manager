<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Source;

class FilterableAttributes implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var array
     */
    private array $options = [];

    /**
     * @param \Magento\Catalog\Model\Layer\FilterableAttributeListInterface $filterableAttributes
     */
    public function __construct(
        protected \Magento\Catalog\Model\Layer\FilterableAttributeListInterface $filterableAttributes
    ) {
    }

    /**
     * Return filterable attribute options as value-label pairs.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        if (!$this->options) {
            foreach ($this->filterableAttributes->getList() as $attribute) {
                $this->options[] = [
                    'value' => $attribute->getAttributeId(),
                    'label' => $attribute->getDefaultFrontendLabel()
                ];
            }
        }

        return $this->options;
    }
}
