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
 * Modify meta robots
 */
class MetaRobotsPlugin
{
    /**
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Inchoo\Seo\Model\Seo\Toolbar $toolbarSeo
     * @param \Inchoo\Seo\Model\ResourceModel\Meta\CustomRobots\CollectionFactory $customRobotsCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\View\Page\Config $pageConfig
     * @param int $maxQueryStringLength
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Config $config,
        protected \Inchoo\Seo\Model\Seo\Toolbar $toolbarSeo,
        protected \Inchoo\Seo\Model\ResourceModel\Meta\CustomRobots\CollectionFactory $customRobotsCollectionFactory,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \Magento\Framework\App\RequestInterface $request,
        protected \Magento\Framework\View\Page\Config $pageConfig,
        protected int $maxQueryStringLength = 4096
    ) {
    }

    /**
     * Modify the meta robots tag before rendering the page.
     *
     * @param \Magento\Framework\View\Page\Config\Renderer $subject
     * @return void
     */
    public function beforeRenderMetadata(\Magento\Framework\View\Page\Config\Renderer $subject): void
    {
        if (!$this->config->isEnabled()) {
            return;
        }

        $this->setCustomMetaRobots();
        $this->setMetaRobotsForNonDefaultToolbarParams();
    }

    /**
     * Apply custom meta robots tag.
     *
     * @return void
     */
    protected function setCustomMetaRobots(): void
    {
        $requestString = $this->request->getRequestString();

        if (strlen($requestString) > $this->maxQueryStringLength) {
            return;
        }

        $storeIds = [
            (int)$this->storeManager->getStore()->getId(),
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        ];

        $collection = $this->customRobotsCollectionFactory->create();
        $collection->addFieldToFilter('store_id', ['in' => $storeIds]);
        $collection->addFieldToFilter('status', 1);
        $collection->setOrder('store_id', 'DESC');
        $collection->setOrder('sort_order', 'ASC');

        /** @var \Inchoo\Seo\Model\Meta\CustomRobots $item */
        foreach ($collection->getItems() as $item) {
            if (fnmatch($item->getUrlPath(), $requestString)) {
                $this->pageConfig->setRobots($item->getMetaRobots());
                break;
            }
        }
    }

    /**
     * Apply meta robots for non-default toolbar parameters (configuration: Non-Default Toolbar Meta Robots).
     *
     * @return void
     */
    protected function setMetaRobotsForNonDefaultToolbarParams(): void
    {
        if (!$nonDefaultToolbarMetaRobots = $this->config->getNonDefaultToolbarMetaRobots()) {
            return;
        }

        foreach ($this->toolbarSeo->getDefaultParamValues(['p']) as $name => $defaultValue) {
            $value = strtolower((string)$this->request->getParam($name));
            if ($value && $value !== strtolower((string)$defaultValue)) {
                $this->pageConfig->setRobots($nonDefaultToolbarMetaRobots);
                return;
            }
        }
    }
}
