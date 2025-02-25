<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Ui\Component\Listing\Column\Store;

class Options extends \Magento\Store\Ui\Component\Listing\Column\Store\Options
{
    /**
     * Get options.
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options) {
            return $this->options;
        }

        $this->currentOptions['All Store Views']['label'] = __('All Store Views');
        $this->currentOptions['All Store Views']['value'] = \Magento\Store\Model\Store::DEFAULT_STORE_ID;

        $this->generateCurrentOptions();

        $this->options = array_values($this->currentOptions);
        return $this->options;
    }
}
