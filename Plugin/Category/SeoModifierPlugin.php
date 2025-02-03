<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Plugin\Category;

class SeoModifierPlugin
{
    /**
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Inchoo\Seo\Model\Modifier\ModifierInterface $categoryModifier
     * @param \Inchoo\Seo\Model\Page\SettingsFactory $pageSettingsFactory
     * @param \Inchoo\Seo\Model\Page\SeoConfig $seoConfig
     * @param \Inchoo\Seo\Model\Filter\Placeholder $placeholderFilter
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Config $config,
        protected \Inchoo\Seo\Model\Modifier\ModifierInterface $categoryModifier,
        protected \Inchoo\Seo\Model\Page\SettingsFactory $pageSettingsFactory,
        protected \Inchoo\Seo\Model\Page\SeoConfig $seoConfig,
        protected \Inchoo\Seo\Model\Filter\Placeholder $placeholderFilter,
        protected \Magento\Catalog\Model\Layer\Resolver $layerResolver
    ) {
    }

    /**
     * Apply category meta rules to the category page.
     *
     * @param \Magento\Catalog\Controller\Category\View $subject
     * @param \Magento\Framework\Controller\AbstractResult $result
     * @return \Magento\Framework\Controller\AbstractResult
     */
    public function afterExecute(
        \Magento\Catalog\Controller\Category\View $subject,
        \Magento\Framework\Controller\AbstractResult $result
    ): \Magento\Framework\Controller\AbstractResult {
        if (!($result instanceof \Magento\Framework\View\Result\Page)
            || !$this->config->isEnabledCategoryMetaRules()
        ) {
            return $result;
        }

        // collect meta settings
        $settings = $this->categoryModifier->modify($this->pageSettingsFactory->create());

        $category = $this->layerResolver->get()->getCurrentCategory();

        // parse {placeholder} values
        foreach ($settings->getData() as $key => $value) {
            if ($value && is_string($value)) {
                $newValue = $this->placeholderFilter->filter($value, $category) ?: null;
                $settings->setData($key, $newValue);
            }
        }

        // apply meta settings
        $this->seoConfig->applySettings($settings);

        if ($description = $settings->getData('description')) {
            $category->setData('description', $description);
        }

        return $result;
    }
}
