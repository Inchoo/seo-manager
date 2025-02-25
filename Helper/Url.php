<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Helper;

class Url
{
    /**
     * Extract the path from the URL.
     *
     * @param string $url
     * @return string
     */
    public function extractUrlPath(string $url): string
    {
        return parse_url($url, PHP_URL_PATH) ?: ''; // phpcs:ignore
    }

    /**
     * Extract the query string from the URL.
     *
     * @param string $url
     * @return string
     */
    public function extractQueryString(string $url): string
    {
        return parse_url($url, PHP_URL_QUERY) ?: ''; // phpcs:ignore
    }

    /**
     * Extract the query parameters from the URL.
     *
     * @param string $url
     * @param array $allowedParams
     * @return array
     */
    public function extractQueryParams(string $url, array $allowedParams = []): array
    {
        $queryParams = [];
        if ($queryString = $this->extractQueryString($url)) {
            parse_str($queryString, $queryParams); // phpcs:ignore
        }

        $result = [];
        foreach ($queryParams as $param => $value) {
            if (!$allowedParams || in_array($param, $allowedParams, true)) {
                $result[$param] = $value;
            }
        }

        return $result;
    }
}
