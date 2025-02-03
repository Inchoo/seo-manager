<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Cms;

class PageRegistry
{
    /**
     * @var \Magento\Cms\Model\Page|null
     */
    private ?\Magento\Cms\Model\Page $page = null;

    /**
     * Set the currently rendered CMS Page.
     *
     * @param \Magento\Cms\Model\Page $page
     * @return $this
     */
    public function set(\Magento\Cms\Model\Page $page): self
    {
        $this->page = $page;
        return $this;
    }

    /**
     * Get the currently rendered CMS Page.
     *
     * @return \Magento\Cms\Model\Page|null
     */
    public function get(): ?\Magento\Cms\Model\Page
    {
        return $this->page;
    }

    /**
     * Clear.
     *
     * @return $this
     */
    public function clear(): self
    {
        $this->page = null;
        return $this;
    }
}
