<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Filter;

class Placeholder
{
    public const PLACEHOLDER_PATTERN = '/{([a-z_|0-9]+)}/';
    public const OPTIONAL_PATTERN    = '/\[.*?\]/';

    /**
     * @param \Inchoo\Seo\Model\Filter\PlaceholderResolverInterface $resolver
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Filter\PlaceholderResolverInterface $resolver
    ) {
    }

    /**
     * Filter [{placeholder}] values.
     *
     * @param string $text
     * @param \Magento\Framework\DataObject $model
     * @return string
     */
    public function filter(string $text, \Magento\Framework\DataObject $model): string
    {
        if (!preg_match_all(self::PLACEHOLDER_PATTERN, $text, $matches, PREG_SET_ORDER)) {
            return $text;
        }

        // resolve matched placeholders to values
        foreach ($matches as $match) {
            foreach (explode('|', $match[1]) as $placeholder) {
                if ($replacedValue = $this->resolver->getValue($placeholder, $model)) {
                    $text = str_replace($match[0], $replacedValue, $text);
                    break; // use first matched placeholder when | is used
                }
            }
        }

        // clean optional placeholders
        $text = preg_replace_callback(
            self::OPTIONAL_PATTERN,
            static function ($matches) {
                // remove square brackets or whole segment if it contains unmatched placeholders
                return false === strpos($matches[0], '{') ? substr($matches[0], 1, -1) : '';
            },
            $text
        );

        // clean unmatched placeholders
        $text = preg_replace(self::PLACEHOLDER_PATTERN, '', $text);

        return (string)$text;
    }
}
