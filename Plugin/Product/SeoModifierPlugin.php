<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Plugin\Product;

class SeoModifierPlugin
{
    /**
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Inchoo\Seo\Model\Modifier\ModifierInterface $productModifier
     * @param \Inchoo\Seo\Model\Page\SettingsFactory $pageSettingsFactory
     * @param \Inchoo\Seo\Model\Page\SeoConfig $seoConfig
     * @param \Inchoo\Seo\Model\Filter\Placeholder $placeholderFilter
     * @param \Magento\Catalog\Helper\Data $catalogHelper
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Config $config,
        protected \Inchoo\Seo\Model\Modifier\ModifierInterface $productModifier,
        protected \Inchoo\Seo\Model\Page\SettingsFactory $pageSettingsFactory,
        protected \Inchoo\Seo\Model\Page\SeoConfig $seoConfig,
        protected \Inchoo\Seo\Model\Filter\Placeholder $placeholderFilter,
        protected \Magento\Catalog\Helper\Data $catalogHelper
    ) {
    }

    /**
     * Apply product meta rules to the product page.
     *
     * @param \Magento\Catalog\Controller\Product\View $subject
     * @param \Magento\Framework\Controller\AbstractResult $result
     * @return \Magento\Framework\Controller\AbstractResult
     */
    public function afterExecute(
        \Magento\Catalog\Controller\Product\View $subject,
        \Magento\Framework\Controller\AbstractResult $result
    ): \Magento\Framework\Controller\AbstractResult {
        if (!($result instanceof \Magento\Framework\View\Result\Page)
            || !$this->config->isEnabled()
            || !$product = $this->catalogHelper->getProduct()
        ) {
            return $result;
        }

        // collect meta settings
        $settings = $this->productModifier->modify($this->pageSettingsFactory->create());

        // parse {placeholder} values
        foreach ($settings->getData() as $key => $value) {
            if ($value && is_string($value)) {
                $newValue = $this->placeholderFilter->filter($value, $product) ?: null;
                $settings->setData($key, $newValue);
            }
        }

        // apply meta settings
        $this->seoConfig->applySettings($settings);

        return $result;
    }
}
