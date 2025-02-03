<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Ui\DataProvider\Listing;

class CategoryRuleDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule\CollectionFactory $collectionFactory
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        protected \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule\CollectionFactory $collectionFactory,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Return collection.
     *
     * @return \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule\Collection
     */
    public function getCollection()
    {
        if (null === $this->collection) {
            $this->collection = $this->collectionFactory->create();
        }

        return $this->collection;
    }
}
