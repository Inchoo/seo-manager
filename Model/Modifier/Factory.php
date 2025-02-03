<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Modifier;

class Factory
{
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        protected \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
    }

    /**
     * Create an object instance.
     *
     * @param string $instanceName
     * @return \Inchoo\Seo\Model\Modifier\ModifierInterface
     */
    public function create(string $instanceName): \Inchoo\Seo\Model\Modifier\ModifierInterface
    {
        return $this->objectManager->create($instanceName);
    }
}
