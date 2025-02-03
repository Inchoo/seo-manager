<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Modifier\Category;

class MetaRules extends \Inchoo\Seo\Model\Modifier\AbstractModifier
{
    /**
     * @param \Inchoo\Seo\Helper\Url $urlHelper
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Inchoo\Seo\Model\Layer\State $layerState
     * @param \Inchoo\Seo\Model\Page\Context $pageContext
     * @param \Inchoo\Seo\Model\Meta\Fallback\CategoryRuleQueue $categoryFallbackQueue
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        protected \Inchoo\Seo\Helper\Url $urlHelper,
        protected \Inchoo\Seo\Model\Config $config,
        protected \Inchoo\Seo\Model\Layer\State $layerState,
        protected \Inchoo\Seo\Model\Page\Context $pageContext,
        protected \Inchoo\Seo\Model\Meta\Fallback\CategoryRuleQueue $categoryFallbackQueue,
        protected \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        protected \Magento\Framework\UrlInterface $url,
        protected \Magento\Framework\App\RequestInterface $request,
        protected \Psr\Log\LoggerInterface $logger
    ) {
    }

    /**
     * Apply the category meta rule metadata.
     *
     * @param \Inchoo\Seo\Model\Page\Settings $settings
     * @return \Inchoo\Seo\Model\Page\Settings
     */
    public function modify(\Inchoo\Seo\Model\Page\Settings $settings): \Inchoo\Seo\Model\Page\Settings
    {
        if (!$this->config->isEnabledCategoryMetaRules()
            || !$currentCategory = $this->layerResolver->get()->getCurrentCategory()
        ) {
            return parent::modify($settings);
        }

        $filterAttributeOptions = $this->layerState->getFilterAttributeOptions();

        try {
            $rulesQueue = $this->categoryFallbackQueue->getRulesQueue($currentCategory, $filterAttributeOptions);
        } catch (\Exception $e) {
            $this->logger->error("Category Meta Rule: {$e->getMessage()}");
            return parent::modify($settings);
        }

        // apply metadata from the rules
        /** @var \Inchoo\Seo\Model\Meta\CategoryRule|\Inchoo\Seo\Model\Meta\ProductRule $rule */
        foreach ($rulesQueue as $rule) {
            $settings->setMetaTitle($rule->getMetaTitle());
            $settings->setMetaKeywords($rule->getMetaKeywords());
            $settings->setMetaDescription($rule->getMetaDescription());
            $settings->setMetaRobots($rule->getMetaRobots());
            $settings->setH1Title($rule->getH1Title());
            $settings->setCanonical($rule->getCanonical());
            if ($rule->getCanonical()) {
                $settings->setCanonical($rule->getCanonical());
            } elseif ($rule->getUseAttributesInCanonical() && $rule->getAttributeIds()) {
                $ruleAttributeIds = explode(',', $rule->getAttributeIds());
                $settings->setCanonical($this->getAttributesCanonical($currentCategory, $ruleAttributeIds));
            }
            if ($ruleDescription = $rule->getDescription()) {
                $settings->setData('description', $ruleDescription);
            }
        }

        if ($canonical = $settings->getCanonical()) {
            // add query strings from the canonical URL to the allowed query parameters list
            $allowedQueryParams = $this->pageContext->getValue('allowed_query_params') ?: [];
            array_push($allowedQueryParams, ...array_keys($this->urlHelper->extractQueryParams($canonical)));
            $this->pageContext->setValue('allowed_query_params', $allowedQueryParams);
        }

        return parent::modify($settings);
    }

    /**
     * Generate the canonical URL for the current category with rule attributes.
     *
     * @param \Magento\Catalog\Model\Category $category
     * @param array $ruleAttributeIds
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getAttributesCanonical(
        \Magento\Catalog\Model\Category $category,
        array $ruleAttributeIds
    ): string {
        $ruleAttributeIds = array_map('intval', $ruleAttributeIds);

        $filterQueryParams = [];
        foreach ($this->layerState->getMetaFilterItems() as $filterItem) {
            $attributeId = $filterItem->getFilter()->getAttributeModel()->getAttributeId();
            if (in_array((int)$attributeId, $ruleAttributeIds, true)) {
                $queryParams = $this->urlHelper->extractQueryParams(
                    $filterItem->getUrl(),
                    [$filterItem->getFilter()->getRequestVar()]
                );
                $filterQueryParams[] = $queryParams;
            }
        }

        $canonical = '';

        // merge/sort query params and create category canonical
        if ($filterQueryParams) {
            $queryParams = array_map(static function ($paramValues) {
                $paramValues = array_unique($paramValues);
                sort($paramValues);
                return $paramValues;
            }, array_merge_recursive(...$filterQueryParams));

            ksort($queryParams);

            $categoryPath = str_replace($this->url->getBaseUrl(), '', $category->getUrl());
            $canonical = "$categoryPath?" . http_build_query($queryParams);
        }

        return $canonical;
    }
}
