<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Ui\Component\Control;

abstract class AbstractButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    /**
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        protected \Magento\Framework\UrlInterface $urlBuilder,
        protected \Magento\Framework\App\RequestInterface $request
    ) {
    }
}
