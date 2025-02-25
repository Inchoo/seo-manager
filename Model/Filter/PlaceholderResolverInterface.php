<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Filter;

interface PlaceholderResolverInterface
{
    /**
     * Get the placeholder value.
     *
     * @param string $placeholder
     * @param \Magento\Framework\DataObject $model
     * @return string
     */
    public function getValue(string $placeholder, \Magento\Framework\DataObject $model): string;
}
