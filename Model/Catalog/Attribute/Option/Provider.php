<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Catalog\Attribute\Option;

class Provider
{
    /**
     * @var array
     */
    protected array $optionsByAttributeId = [];

    /**
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $optionCollectionFactory
     */
    public function __construct(
        protected \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $optionCollectionFactory,
    ) {
    }

    /**
     * Get the attribute options.
     *
     * @param int $attributeId
     * @return array
     */
    public function get(int $attributeId): array
    {
        if (isset($this->optionsByAttributeId[$attributeId])) {
            return $this->optionsByAttributeId[$attributeId];
        }

        $collection = $this->optionCollectionFactory->create();
        $collection->setAttributeFilter((int)$attributeId);
        $collection->setPositionOrder();
        $collection->setStoreFilter();

        $this->optionsByAttributeId[$attributeId] = $collection->toOptionArray();
        return $this->optionsByAttributeId[$attributeId];
    }
}
