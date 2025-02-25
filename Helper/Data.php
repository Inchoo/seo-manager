<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Helper;

class Data
{
    /**
     * Stringify and sort the attribute options.
     *
     * @param array $attributesOptions
     * @return string
     * @throws \JsonException
     */
    public function stringifyAttributeOptions(array $attributesOptions): string
    {
        usort($attributesOptions, static function ($a, $b) {
            if ($a['attribute'] === $b['attribute']) {
                if ($a['option'] === null && $b['option'] === null) {
                    return 0;
                }
                if ($a['option'] === null) {
                    return -1;
                }
                if ($b['option'] === null) {
                    return 1;
                }
                return $a['option'] <=> $b['option'];
            }
            return $a['attribute'] <=> $b['attribute'];
        });

        return json_encode($attributesOptions, JSON_THROW_ON_ERROR);
    }
}
