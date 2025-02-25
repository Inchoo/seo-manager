<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Seo;

/**
 * Generate meta URLs from Pager block
 */
class Pagination
{
    /**
     * @param \Inchoo\Seo\Model\Seo\Toolbar $toolbarSeo
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Seo\Toolbar $toolbarSeo,
        protected \Magento\Framework\UrlInterface $urlBuilder,
        protected \Magento\Framework\App\RequestInterface $request
    ) {
    }

    /**
     * Check if the pager block is initialized.
     *
     * @param \Magento\Theme\Block\Html\Pager $pagerBlock
     * @return bool
     */
    protected function isPagerInitialized(\Magento\Theme\Block\Html\Pager $pagerBlock): bool
    {
        return (bool)$pagerBlock->getCollection();
    }

    /**
     * Generate the canonical URL.
     *
     * @param \Magento\Theme\Block\Html\Pager $pagerBlock
     * @param string $currentCanonicalUrl
     * @return string
     */
    public function getCanonicalUrl(
        \Magento\Theme\Block\Html\Pager $pagerBlock,
        string $currentCanonicalUrl
    ): string {
        if (!$this->isPagerInitialized($pagerBlock)) {
            return $currentCanonicalUrl;
        }

        if (!$currentCanonicalUrl) {
            $path = $pagerBlock->getData('path') ?: '*/*/*';
            $urlParams = [
                '_current' => true,
                '_escape' => true,
                '_use_rewrite' => true
            ];

            return $pagerBlock->getUrl($path, $urlParams);
        }

        $query = [];
        foreach ($this->toolbarSeo->getDefaultParamValues() as $name => $defaultValue) {
            $value = strtolower((string)$this->request->getParam($name));
            if ($value && $value !== strtolower((string)$defaultValue)) {
                $query[$name] = $value;
            }
        }

        $canonicalPath = str_replace($this->urlBuilder->getBaseUrl(), '', $currentCanonicalUrl);

        return $this->urlBuilder->getDirectUrl($canonicalPath, [
            '_escape' => true,
            '_query' => $query
        ]);
    }

    /**
     * Generate the previous page URL.
     *
     * @param \Magento\Theme\Block\Html\Pager $pagerBlock
     * @return string
     */
    public function getRelPrevUrl(\Magento\Theme\Block\Html\Pager $pagerBlock): string
    {
        if (!$this->isPagerInitialized($pagerBlock)) {
            return '';
        }

        $currentPage = (int)$pagerBlock->getCurrentPage();
        $url = '';

        if ($currentPage === 2) { // first page doesn't need ?p=1
            $url = $pagerBlock->getPagerUrl([$pagerBlock->getPageVarName() => null]);
        } elseif ($currentPage > 2) {
            $url = $pagerBlock->getPreviousPageUrl();
        }

        return $url;
    }

    /**
     * Generate the next page URL.
     *
     * @param \Magento\Theme\Block\Html\Pager $pagerBlock
     * @return string
     */
    public function getRelNextUrl(\Magento\Theme\Block\Html\Pager $pagerBlock): string
    {
        if (!$this->isPagerInitialized($pagerBlock)) {
            return '';
        }

        $url = '';
        if ($pagerBlock->getCurrentPage() < $pagerBlock->getLastPageNum()) {
            $url = $pagerBlock->getNextPageUrl();
        }

        return $url;
    }
}
