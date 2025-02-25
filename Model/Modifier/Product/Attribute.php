<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Modifier\Product;

class Attribute extends \Inchoo\Seo\Model\Modifier\AbstractModifier
{
    /**
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Magento\Catalog\Helper\Data $catalogHelper
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Config $config,
        protected \Magento\Catalog\Helper\Data $catalogHelper
    ) {
    }

    /**
     * Apply the product attribute metadata.
     *
     * @param \Inchoo\Seo\Model\Page\Settings $settings
     * @return \Inchoo\Seo\Model\Page\Settings
     */
    public function modify(\Inchoo\Seo\Model\Page\Settings $settings): \Inchoo\Seo\Model\Page\Settings
    {
        if (!$this->config->isEnabledProductMetaRules() || !$product = $this->catalogHelper->getProduct()) {
            return parent::modify($settings);
        }

        if (!$settings->getMetaTitle()) {
            $settings->setMetaTitle($product->getData('meta_title'));
        }
        if (!$settings->getMetaKeywords()) {
            $settings->setMetaKeywords($product->getData('meta_keywords'));
        }
        if (!$settings->getMetaDescription()) {
            $settings->setMetaDescription($product->getData('meta_description'));
        }
        if (!$settings->getMetaRobots()) {
            $settings->setMetaRobots($product->getData('meta_robots'));
        }
        if (!$settings->getH1Title()) {
            $settings->setH1Title($product->getData('h1_title'));
        }

        return parent::modify($settings);
    }
}
