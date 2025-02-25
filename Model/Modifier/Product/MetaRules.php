<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Modifier\Product;

class MetaRules extends \Inchoo\Seo\Model\Modifier\AbstractModifier
{
    /**
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Inchoo\Seo\Model\Meta\Fallback\ProductRuleQueue $productFallbackQueue
     * @param \Magento\Catalog\Helper\Data $catalogHelper
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Config $config,
        protected \Inchoo\Seo\Model\Meta\Fallback\ProductRuleQueue $productFallbackQueue,
        protected \Magento\Catalog\Helper\Data $catalogHelper,
        protected \Psr\Log\LoggerInterface $logger
    ) {
    }

    /**
     * Apply the product meta rule metadata.
     *
     * @param \Inchoo\Seo\Model\Page\Settings $settings
     * @return \Inchoo\Seo\Model\Page\Settings
     */
    public function modify(\Inchoo\Seo\Model\Page\Settings $settings): \Inchoo\Seo\Model\Page\Settings
    {
        if (!$this->config->isEnabledProductMetaRules()
            || !$product = $this->catalogHelper->getProduct()
        ) {
            return parent::modify($settings);
        }

        try {
            $rulesQueue = $this->productFallbackQueue->getRulesQueue($product);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->logger->error("Product Meta Rule: {$e->getMessage()}");
            return parent::modify($settings);
        }

        // apply metadata from the rules
        foreach ($rulesQueue as $metaRule) {
            $settings->setMetaTitle($metaRule->getMetaTitle());
            $settings->setMetaKeywords($metaRule->getMetaKeywords());
            $settings->setMetaDescription($metaRule->getMetaDescription());
            $settings->setMetaRobots($metaRule->getMetaRobots());
            $settings->setH1Title($metaRule->getH1Title());
        }

        return parent::modify($settings);
    }
}
