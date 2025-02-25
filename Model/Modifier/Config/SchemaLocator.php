<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Modifier\Config;

class SchemaLocator implements \Magento\Framework\Config\SchemaLocatorInterface
{
    /**
     * @param \Magento\Framework\Module\Dir\Reader $moduleReader
     */
    public function __construct(
        protected \Magento\Framework\Module\Dir\Reader $moduleReader
    ) {
    }

    /**
     * Get path to merged config schema.
     *
     * @return string|null
     */
    public function getSchema()
    {
        return $this->getModuleEtcDir() . DIRECTORY_SEPARATOR . 'meta_modifiers.xsd';
    }

    /**
     * Get path to per file validation schema.
     *
     * @return string|null
     */
    public function getPerFileSchema()
    {
        return $this->getModuleEtcDir() . DIRECTORY_SEPARATOR . 'meta_modifiers.xsd';
    }

    /**
     * Get the module directory path.
     *
     * @return string
     */
    protected function getModuleEtcDir(): string
    {
        return $this->moduleReader->getModuleDir(\Magento\Framework\Module\Dir::MODULE_ETC_DIR, 'Inchoo_Seo');
    }
}
