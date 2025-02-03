<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Observer\Catalog\Category;

class CanonicalPrevNextObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Inchoo\Seo\Model\Seo\Pagination $paginationSeo
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param \Magento\Framework\View\Page\Config $pageConfig
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Config $config,
        protected \Inchoo\Seo\Model\Seo\Pagination $paginationSeo,
        protected \Magento\Catalog\Helper\Category $categoryHelper,
        protected \Magento\Framework\View\LayoutInterface $layout,
        protected \Magento\Framework\View\Page\Config $pageConfig
    ) {
    }

    /**
     * Set the canonical, prev, and next URLs on the paginated category page.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->config->useCanonicalLinkMetaTagForCategories()) {
            return;
        }

        /** @var \Magento\Catalog\Block\Product\ListProduct $listProductBlock */
        if (!$listProductBlock = $this->layout->getBlock('category.products.list')) {
            return;
        }

        // we don't want to modify existing collection
        $collection = clone $listProductBlock->getLayer()->getProductCollection();
        $pagerBlock = $this->getPagerBlock($collection, $listProductBlock);

        // canonical
        if ($this->categoryHelper->canUseCanonicalTag()
            && $canonicalUrl = $this->paginationSeo->getCanonicalUrl($pagerBlock, $this->getCurrentCanonical())
        ) {
            $this->removeExistingCanonical();
            $this->pageConfig->addRemotePageAsset(
                $canonicalUrl,
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
        }

        // prev
        if ($prevUrl = $this->paginationSeo->getRelPrevUrl($pagerBlock)) {
            $this->pageConfig->addRemotePageAsset(
                $prevUrl,
                'link_rel',
                ['attributes' => ['rel' => 'prev']]
            );
        }

        // next
        if ($nextUrl = $this->paginationSeo->getRelNextUrl($pagerBlock)) {
            $this->pageConfig->addRemotePageAsset(
                $nextUrl,
                'link_rel',
                ['attributes' => ['rel' => 'next']]
            );
        }
    }

    /**
     * We are initializing a dummy Pager block because the existing
     * 'product_list_toolbar_pager' block has not been initialized yet.
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Magento\Catalog\Block\Product\ListProduct $listProductBlock
     * @return \Magento\Theme\Block\Html\Pager
     */
    protected function getPagerBlock(
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection,
        \Magento\Catalog\Block\Product\ListProduct $listProductBlock
    ): \Magento\Theme\Block\Html\Pager {
        $toolbarBlock = $listProductBlock->getToolbarBlock();

        $pagerBlock = $this->layout->createBlock(\Magento\Theme\Block\Html\Pager::class);
        $pagerBlock->setAvailableLimit($toolbarBlock->getAvailableLimit());
        $pagerBlock->setLimit($toolbarBlock->getLimit());
        $pagerBlock->setCollection($collection);

        return $pagerBlock;
    }

    /**
     * Get the current canonical URL.
     *
     * @return string
     */
    protected function getCurrentCanonical(): string
    {
        $result = '';
        foreach ($this->pageConfig->getAssetCollection()->getAll() as $asset) {
            if ($asset->getContentType() === 'canonical') {
                $result = $asset->getUrl();
            }
        }

        return $result;
    }

    /**
     * Remove the existing canonical URL.
     *
     * @return void
     */
    protected function removeExistingCanonical(): void
    {
        $pageAssets = $this->pageConfig->getAssetCollection();

        foreach ($pageAssets->getAll() as $asset) {
            if ($asset->getContentType() === 'canonical') {
                $pageAssets->remove($asset->getUrl());
            }
        }
    }
}
