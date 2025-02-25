<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Modifier;

/**
 * Use this class as type for modifier virtualType.
 */
class Base extends \Inchoo\Seo\Model\Modifier\AbstractModifier
{
    /**
     * @var bool
     */
    private bool $initialized = false;

    /**
     * @param \Inchoo\Seo\Model\Modifier\Factory $modifierFactory
     * @param \Inchoo\Seo\Model\Modifier\ConfigInterface $modifierConfig
     * @param string $modifierGroup
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Modifier\Factory $modifierFactory,
        protected \Inchoo\Seo\Model\Modifier\ConfigInterface $modifierConfig,
        protected string $modifierGroup
    ) {
    }

    /**
     * Modify the meta data.
     *
     * @param \Inchoo\Seo\Model\Page\Settings $settings
     * @return \Inchoo\Seo\Model\Page\Settings
     */
    public function modify(\Inchoo\Seo\Model\Page\Settings $settings): \Inchoo\Seo\Model\Page\Settings
    {
        if (!$this->initialized) {
            $this->initChain();
            $this->initialized = true;
        }
        return parent::modify($settings);
    }

    /**
     * Init modifiers.
     *
     * @return void
     */
    private function initChain(): void
    {
        $next = $this;
        foreach ($this->modifierConfig->getModifiers($this->modifierGroup) as $instanceName) {
            $next = $next->setNext($this->modifierFactory->create($instanceName));
        }
    }
}
