<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Observer\CatalogSearch;

class MetaRobotsObserver implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Magento\Framework\View\Page\Config $pageConfig
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Config $config,
        protected \Magento\Framework\View\Page\Config $pageConfig
    ) {
    }

    /**
     * Set the meta robots tag on the catalog search results page.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->config->isEnabled() && $robots = $this->config->getCatalogSearchMetaRobots()) {
            $this->pageConfig->setRobots($robots);
        }
    }
}
