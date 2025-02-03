<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Page;

class SeoConfig
{
    /**
     * @param \Inchoo\Seo\Model\Config $config
     * @param \Magento\Framework\View\Page\Config $pageConfig
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param \Magento\Framework\Filter\StripTags $tagFilter
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Framework\UrlInterface $urlBuilder
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Config $config,
        protected \Magento\Framework\View\Page\Config $pageConfig,
        protected \Magento\Framework\View\LayoutInterface $layout,
        protected \Magento\Framework\Filter\StripTags $tagFilter,
        protected \Magento\Framework\Stdlib\StringUtils $string,
        protected \Magento\Framework\UrlInterface $urlBuilder
    ) {
    }

    /**
     * Available settings:
     *   - meta_title
     *   - meta_keywords
     *   - meta_description
     *   - meta_robots
     *   - h1_title
     *
     * @param \Inchoo\Seo\Model\Page\Settings $settings
     * @return self
     */
    public function applySettings(\Inchoo\Seo\Model\Page\Settings $settings): self
    {
        if ($metaTitle = $settings->getMetaTitle()) {
            $this->setMetaTitle($metaTitle);
        }
        if ($metaKeywords = $settings->getMetaKeywords()) {
            $this->setMetaKeywords($metaKeywords);
        }
        if ($metaDescription = $settings->getMetaDescription()) {
            $this->setMetaDescription($metaDescription);
        }
        if ($metaRobots = $settings->getMetaRobots()) {
            $this->setMetaRobots($metaRobots);
        }
        if ($this->config->useCanonicalLinkMetaTagForCategories() && $canonical = $settings->getCanonical()) {
            $this->setCanonical($canonical);
        }
        if ($h1Title = $settings->getH1Title()) {
            $this->setH1Title($h1Title);
        }

        return $this;
    }

    /**
     * Set meta title.
     *
     * @param string $metaTitle
     * @return self
     */
    public function setMetaTitle(string $metaTitle): self
    {
        $this->pageConfig->getTitle()->set($metaTitle);
        $this->pageConfig->setMetaTitle($metaTitle);
        return $this;
    }

    /**
     * Set meta keywords.
     *
     * @param string $metaKeywords
     * @return self
     */
    public function setMetaKeywords(string $metaKeywords): self
    {
        $this->pageConfig->setKeywords($metaKeywords);
        return $this;
    }

    /**
     * Set meta description.
     *
     * @param string $metaDescription
     * @return self
     */
    public function setMetaDescription(string $metaDescription): self
    {
        if ($metaDescription) {
            $metaDescription = $this->tagFilter->filter($metaDescription); // strip_tags
            $metaDescription = $this->string->substr($metaDescription, 0, 255);
        }

        $this->pageConfig->setDescription($metaDescription);
        return $this;
    }

    /**
     * Set canonical.
     *
     * @param string $canonical
     * @return self
     */
    public function setCanonical(string $canonical): self
    {
        $canonicalPath = str_replace($this->urlBuilder->getBaseUrl(), '', $canonical);
        $canonicalUrl = $this->urlBuilder->getDirectUrl($canonicalPath);

        $this->pageConfig->addRemotePageAsset(
            $canonicalUrl,
            'canonical',
            ['attributes' => ['rel' => 'canonical']]
        );

        return $this;
    }

    /**
     * Set meta robots.
     *
     * @param string $metaRobots
     * @return self
     */
    public function setMetaRobots(string $metaRobots): self
    {
        $this->pageConfig->setRobots($metaRobots);
        return $this;
    }

    /**
     * Sets or modifies current meta robots. It's possible to modify one parameter.
     *
     * @param string|null $param1
     * @param string|null $param2
     * @return self
     */
    public function setMetaRobotsByParameters(string $param1 = null, string $param2 = null): self
    {
        try {
            $currentParameters = explode(',', (string)$this->pageConfig->getRobots());
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $currentParameters = ['INDEX', 'FOLLOW'];
        }

        if ($param1) {
            $currentParameters[0] = $param1;
        }
        if ($param2) {
            $currentParameters[1] = $param2;
        }

        return $this->setMetaRobots(implode(',', $currentParameters));
    }

    /**
     * Set H1 title.
     *
     * @param string $h1Title
     * @return self
     */
    public function setH1Title(string $h1Title): self
    {
        if ($pageMainTitle = $this->layout->getBlock('page.main.title')) {
            $pageMainTitle->setPageTitle($h1Title);
        }
        return $this;
    }
}
