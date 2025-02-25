<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\ResourceModel\CustomCanonical;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize collection.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Inchoo\Seo\Model\CustomCanonical::class,
            \Inchoo\Seo\Model\ResourceModel\CustomCanonical::class
        );
        $this->_setIdFieldName('entity_id');
    }
}
