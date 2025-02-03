<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Plugin\Category\Form;

class SetupUseDefaultValueCheckbox
{
    /**
     * @param \Magento\Framework\Stdlib\ArrayManager $arrayManager
     */
    public function __construct(
        protected \Magento\Framework\Stdlib\ArrayManager $arrayManager
    ) {
    }

    /**
     * Workaround to automatically add "Use Default Value" checkbox to custom fields.
     *
     * @param \Magento\Catalog\Model\Category\DataProvider $subject
     * @param array $meta
     * @return array
     */
    public function afterPrepareMeta(\Magento\Catalog\Model\Category\DataProvider $subject, array $meta): array
    {
        foreach (['meta_robots', 'h1_title'] as $attributeCode) {
            $meta = $this->arrayManager->set(
                "search_engine_optimization/children/{$attributeCode}/arguments/data/config",
                $meta,
                []
            );
        }

        return $meta;
    }
}
