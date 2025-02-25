<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Observer\Cms\Page;

class MetaRobotsCanonicalUrlObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Inchoo\Seo\Model\Cms\PageRegistry $pageRegistry
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\View\Page\Config $pageConfig
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Config $config,
        protected \Inchoo\Seo\Model\Cms\PageRegistry $pageRegistry,
        protected \Magento\Framework\UrlInterface $url,
        protected \Magento\Framework\View\Page\Config $pageConfig
    ) {
    }

    /**
     * Set the meta robots tag and canonical URL on the CMS page.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->config->isEnabled() || !$page = $this->pageRegistry->get()) {
            return;
        }

        // add meta robots
        if ($robots = $page->getData('meta_robots')) {
            $this->pageConfig->setRobots($robots);
        }

        // add canonical URL
        if ($this->config->useCanonicalLinkMetaTagForCmsPages()) {
            // fallback to identifier
            $canonicalUrl = $page->getData('canonical_url') ?: $this->extractPageIdentifier($page);
            $this->pageConfig->addRemotePageAsset(
                $this->url->getUrl($canonicalUrl, ['_direct' => $canonicalUrl]),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
        }
    }

    /**
     * Extract the CMS page identifier.
     *
     * @param \Magento\Cms\Model\Page $page
     * @return string
     */
    protected function extractPageIdentifier(\Magento\Cms\Model\Page $page): string
    {
        // use empty string for homepage
        if ($page->getIdentifier() === $this->config->getCmsHomePageIdentifier()) {
            return '';
        }

        return (string)$page->getIdentifier();
    }
}
