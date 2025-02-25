<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Setup\Patch\Data;

class AddProductSeoAttributes implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    /**
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        protected \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
    ) {
    }

    /**
     * Get array of patches that have to be executed prior to this.
     *
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Get aliases (previous names) for the patch.
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Create the "meta_robots" and "h1_title" product attributes.
     *
     * @return self
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function apply()
    {
        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create();

        $entityType = \Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE;

        $eavSetup->addAttribute($entityType, 'meta_robots', [
            'label' => 'Meta Robots',
            'required' => 0,
            'user_defined' => 1,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE
        ]);

        $eavSetup->addAttribute($entityType, 'h1_title', [
            'label' => 'Page H1 Title',
            'required' => 0,
            'user_defined' => 1,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE
        ]);

        foreach ($eavSetup->getAllAttributeSetIds($entityType) as $setId) {
            $eavSetup->addAttributeToSet($entityType, $setId, 'search-engine-optimization', 'meta_robots');
            $eavSetup->addAttributeToSet($entityType, $setId, 'search-engine-optimization', 'h1_title');
        }

        return $this;
    }
}
