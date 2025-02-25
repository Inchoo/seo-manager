<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Plugin;

/**
 * Modify canonical URL
 */
class CanonicalPlugin
{
    /**
     * @param \Inchoo\Seo\Helper\Url $urlHelper
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Inchoo\Seo\Model\Page\Context $pageContext
     * @param \Inchoo\Seo\Model\ResourceModel\CustomCanonical $customCanonicalResource
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\View\Page\Config $pageConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        protected \Inchoo\Seo\Helper\Url $urlHelper,
        protected \Inchoo\Seo\Model\Config $config,
        protected \Inchoo\Seo\Model\Page\Context $pageContext,
        protected \Inchoo\Seo\Model\ResourceModel\CustomCanonical $customCanonicalResource,
        protected \Magento\Framework\UrlInterface $urlBuilder,
        protected \Magento\Framework\View\Page\Config $pageConfig,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
    }

    /**
     * Modify the canonical URL before rendering the page
     * (custom canonical, robots NOINDEX tag, and query filtering).
     *
     * @see \Magento\Framework\View\Page\Config\Renderer::renderAssets()
     *
     * @param \Magento\Framework\View\Page\Config\Renderer $subject
     * @return void
     */
    public function beforeRenderAssets(\Magento\Framework\View\Page\Config\Renderer $subject): void
    {
        if (!$this->config->isEnabled()) {
            return;
        }

        $customCanonicalUrl = $this->getCustomCanonicalUrl();

        $canonicalExists = false;
        $pageAssets = $this->pageConfig->getAssetCollection();
        foreach ($pageAssets->getAll() as $identifier => $asset) {
            if ($asset->getContentType() !== 'canonical') {
                continue;
            }

            $canonicalExists = true;

            // remove canonical when robots are NOINDEX
            if ($this->isRobotsNoindex()) {
                $pageAssets->remove($identifier);
                continue;
            }

            $canonicalUrl = $customCanonicalUrl ?: $asset->getUrl();

            // filter canonical query string
            $filteredUrl = $this->filterQueryString($canonicalUrl);

            // assign filtered canonical URL
            if ($asset->getUrl() !== $filteredUrl) {
                $pageAssets->remove($identifier);
                $this->pageConfig->addRemotePageAsset($filteredUrl, 'canonical', [
                    'attributes' => ['rel' => 'canonical']
                ]);
            }
        }

        // add custom canonical URL if canonical doesn't exist
        if ($customCanonicalUrl && !$canonicalExists && !$this->isRobotsNoindex()) {
            $this->pageConfig->addRemotePageAsset(
                $this->filterQueryString($customCanonicalUrl),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
        }
    }

    /**
     * Check whether the current page has a robots NOINDEX tag.
     *
     * @return bool
     */
    protected function isRobotsNoindex(): bool
    {
        try {
            $robots = $this->pageConfig->getRobots();
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return false;
        }

        return false !== stripos($robots, 'NOINDEX');
    }

    /**
     * Remove disallowed query parameters from the canonical URL.
     *
     * @param string $canonicalUrl
     * @return string
     */
    protected function filterQueryString(string $canonicalUrl): string
    {
        if (!$this->config->isEnabledCanonicalLinkFilter()) {
            return $canonicalUrl;
        }

        $baseUrl = strstr($canonicalUrl, '?', true);
        if (!$baseUrl || !$queryParams = $this->urlHelper->extractQueryParams($canonicalUrl)) {
            return $canonicalUrl;
        }

        $allowedQueryParams = $this->config->getAllowedCanonicalQueryStrings();
        if ($contextAllowedQueryParams = $this->pageContext->getValue('allowed_query_params')) {
            array_push($allowedQueryParams, ...$contextAllowedQueryParams); // allow query params from meta rules
        }

        foreach ($queryParams as $param => $value) {
            if (!in_array($param, $allowedQueryParams, true)) {
                unset($queryParams[$param]);
            }
        }

        $newCanonical = $baseUrl;
        if ($queryParams) {
            $newCanonical .= '?' . http_build_query($queryParams);
        }

        return $newCanonical;
    }

    /**
     * Check whether a custom canonical URL path exists for the current URL path.
     *
     * @return string
     */
    protected function getCustomCanonicalUrl(): string
    {
        $currentUrl = $this->urlBuilder->getCurrentUrl();
        $urlPath = $this->urlHelper->extractUrlPath($currentUrl);

        if ($urlPath !== '/') {
            $urlPath = ltrim($urlPath, '/');
        }

        $customCanonicalPath = $this->customCanonicalResource->getTargetPath(
            $urlPath,
            (int)$this->storeManager->getStore()->getId()
        );

        if (!$customCanonicalPath) {
            return ''; // nothing found
        }

        $canonicalUrl = $this->urlBuilder->getDirectUrl(ltrim($customCanonicalPath, '/'));
        if ($queryString = $this->urlHelper->extractQueryString($currentUrl)) {
            $canonicalUrl .= "?{$queryString}";
        }

        return $canonicalUrl;
    }
}
