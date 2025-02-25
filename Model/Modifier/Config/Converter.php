<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Modifier\Config;

class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * Convert config.
     *
     * @param mixed $source
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function convert($source)
    {
        $output = [];

        if (!$source instanceof \DOMDocument) {
            return $output;
        }

        /** @var \DOMElement $group */
        foreach ($source->getElementsByTagName('group') as $group) {
            $groupData = [];

            /** @var \DOMElement $item */
            foreach ($group->getElementsByTagName('item') as $item) {
                if ($item->getAttribute('disabled')) {
                    continue;
                }

                if ($item->getAttribute('instance') === '' || $item->getAttribute('sortOrder') === '') {
                    throw new \Magento\Framework\Exception\LocalizedException(
                        __('meta_modifiers.xml misses instance or sortOrder attributes.')
                    );
                }

                $groupData[] = [
                    'name' => $item->getAttribute('name'),
                    'instance' => $item->getAttribute('instance'),
                    'sortOrder' => $item->getAttribute('sortOrder')
                ];
            }

            usort($groupData, static function ($a, $b) {
                return $a['sortOrder'] <=> $b['sortOrder'];
            });

            $output[$group->getAttribute('name')] = array_column($groupData, 'instance', 'name');
        }

        return $output;
    }
}
