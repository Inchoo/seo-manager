<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Source;

class RobotsMetaTag implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var array
     */
    private array $options = [];

    /**
     * Return meta robots options as value-label pairs.
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options[] = ['value' => '', 'label' => __('-- Please Select --')];

            foreach ($this->getContentValues() as $value) {
                $this->options[] = [
                    'value' => $value,
                    'label' => $value
                ];
            }
        }

        return $this->options;
    }

    /**
     * Get meta robots values.
     *
     * @return array
     */
    private function getContentValues(): array
    {
        return [
            'INDEX,FOLLOW',
            'INDEX,NOFOLLOW',
            'NOINDEX,FOLLOW',
            'NOINDEX,NOFOLLOW'
        ];
    }
}
