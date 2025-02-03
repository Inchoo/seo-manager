<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Modifier;

abstract class AbstractModifier implements \Inchoo\Seo\Model\Modifier\ModifierInterface
{
    /**
     * @var ModifierInterface|null
     */
    private ?\Inchoo\Seo\Model\Modifier\ModifierInterface $nextModifier = null;

    /**
     * Set the next modifier in the chain.
     *
     * @param \Inchoo\Seo\Model\Modifier\ModifierInterface $modifier
     * @return \Inchoo\Seo\Model\Modifier\ModifierInterface
     */
    public function setNext(
        \Inchoo\Seo\Model\Modifier\ModifierInterface $modifier
    ): \Inchoo\Seo\Model\Modifier\ModifierInterface {
        $this->nextModifier = $modifier;
        return $modifier;
    }

    /**
     * Modify the meta data.
     *
     * @param \Inchoo\Seo\Model\Page\Settings $settings
     * @return \Inchoo\Seo\Model\Page\Settings
     */
    public function modify(\Inchoo\Seo\Model\Page\Settings $settings): \Inchoo\Seo\Model\Page\Settings
    {
        if (!$this->nextModifier) {
            return $settings;
        }
        return $this->nextModifier->modify($settings);
    }
}
