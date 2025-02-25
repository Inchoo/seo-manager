<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Page;

class Settings extends \Magento\Framework\DataObject
{
    /**
     * Get meta title.
     *
     * @return string|null
     */
    public function getMetaTitle(): ?string
    {
        return $this->_getData('meta_title');
    }

    /**
     * Set meta title.
     *
     * @param string|null $metaTitle
     * @return $this
     */
    public function setMetaTitle(?string $metaTitle): self
    {
        if ($metaTitle) {
            $this->setData('meta_title', $metaTitle);
        }
        return $this;
    }

    /**
     * Get meta keywords.
     *
     * @return string|null
     */
    public function getMetaKeywords(): ?string
    {
        return $this->_getData('meta_keywords');
    }

    /**
     * Set meta keywords.
     *
     * @param string|null $metaKeywords
     * @return $this
     */
    public function setMetaKeywords(?string $metaKeywords): self
    {
        if ($metaKeywords) {
            $this->setData('meta_keywords', $metaKeywords);
        }
        return $this;
    }

    /**
     * Get meta description.
     *
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        return $this->_getData('meta_description');
    }

    /**
     * Set meta description.
     *
     * @param string|null $metaDescription
     * @return $this
     */
    public function setMetaDescription(?string $metaDescription): self
    {
        if ($metaDescription) {
            $this->setData('meta_description', $metaDescription);
        }
        return $this;
    }

    /**
     * Get canonical URL.
     *
     * @return string|null
     */
    public function getCanonical(): ?string
    {
        return $this->_getData('canonical');
    }

    /**
     * Set canonical URL.
     *
     * @param string|null $canonical
     * @return $this
     */
    public function setCanonical(?string $canonical): self
    {
        if ($canonical) {
            $this->setData('canonical', $canonical);
        }
        return $this;
    }

    /**
     * Get meta robots.
     *
     * @return string|null
     */
    public function getMetaRobots(): ?string
    {
        return $this->_getData('meta_robots');
    }

    /**
     * Set meta robots.
     *
     * @param string|null $metaRobots
     * @return $this
     */
    public function setMetaRobots(?string $metaRobots): self
    {
        if ($metaRobots) {
            $this->setData('meta_robots', $metaRobots);
        }
        return $this;
    }

    /**
     * Get H1 title.
     *
     * @return string|null
     */
    public function getH1Title(): ?string
    {
        return $this->_getData('h1_title');
    }

    /**
     * Set H1 title.
     *
     * @param string|null $h1Title
     * @return $this
     */
    public function setH1Title(?string $h1Title): self
    {
        if ($h1Title) {
            $this->setData('h1_title', $h1Title);
        }
        return $this;
    }
}
