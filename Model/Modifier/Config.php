<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Modifier;

class Config implements \Inchoo\Seo\Model\Modifier\ConfigInterface
{
    /**
     * @param \Magento\Framework\Config\DataInterface $dataStorage
     */
    public function __construct(
        protected \Magento\Framework\Config\DataInterface $dataStorage
    ) {
    }

    /**
     * Get modifiers from the XML config file.
     *
     * @param string $group
     * @return string[]
     */
    public function getModifiers(string $group): array
    {
        return $this->dataStorage->get($group, []);
    }
}
