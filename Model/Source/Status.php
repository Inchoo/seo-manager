<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Source;

class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    public const STATUS_DISABLED = 0;
    public const STATUS_ENABLED  = 1;

    /**
     * Return enabled/disabled options as value-label pairs.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::STATUS_DISABLED, 'label' => __('Disabled')],
            ['value' => self::STATUS_ENABLED, 'label' => __('Enabled')]
        ];
    }
}
