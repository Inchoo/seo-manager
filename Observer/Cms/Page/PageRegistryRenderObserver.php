<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Observer\Cms\Page;

class PageRegistryRenderObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Inchoo\Seo\Model\Cms\PageRegistry $pageRegistry
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Cms\PageRegistry $pageRegistry
    ) {
    }

    /**
     * Store an instance of the currently rendered CMS Page.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->pageRegistry->set($observer->getData('page'));
    }
}
