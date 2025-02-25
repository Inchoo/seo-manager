<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Modifier;

interface ConfigInterface
{
    /**
     * Get modifiers from the XML config file.
     *
     * @param string $group
     * @return string[]
     */
    public function getModifiers(string $group): array;
}
