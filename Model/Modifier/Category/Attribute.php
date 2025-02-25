<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Modifier\Category;

class Attribute extends \Inchoo\Seo\Model\Modifier\AbstractModifier
{
    /**
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Inchoo\Seo\Model\Layer\State $layerState
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Config $config,
        protected \Inchoo\Seo\Model\Layer\State $layerState,
        protected \Magento\Catalog\Model\Layer\Resolver $layerResolver,
    ) {
    }

    /**
     * Apply the category attribute metadata.
     *
     * @param \Inchoo\Seo\Model\Page\Settings $settings
     * @return \Inchoo\Seo\Model\Page\Settings
     */
    public function modify(\Inchoo\Seo\Model\Page\Settings $settings): \Inchoo\Seo\Model\Page\Settings
    {
        if (!$this->config->isEnabledCategoryMetaRules()) {
            return parent::modify($settings);
        }

        $currentCategory = $this->layerResolver->get()->getCurrentCategory();
        $isFiltered = (bool)$this->layerState->getFilterAttributeOptions();

        // unfiltered category has the biggest priority
        if (!$isFiltered || !$settings->getMetaTitle()) {
            $settings->setMetaTitle($currentCategory->getData('meta_title'));
        }
        if (!$isFiltered || !$settings->getMetaKeywords()) {
            $settings->setMetaKeywords($currentCategory->getData('meta_keywords'));
        }
        if (!$isFiltered || !$settings->getMetaDescription()) {
            $settings->setMetaDescription($currentCategory->getData('meta_description'));
        }
        if (!$isFiltered || !$settings->getMetaRobots()) {
            $settings->setMetaRobots($currentCategory->getData('meta_robots'));
        }
        if (!$isFiltered || !$settings->getH1Title()) {
            $settings->setH1Title($currentCategory->getData('h1_title'));
        }
        if (!$isFiltered || !$settings->getData('description')) {
            $settings->setData('description', $currentCategory->getData('description'));
        }

        return parent::modify($settings);
    }
}
