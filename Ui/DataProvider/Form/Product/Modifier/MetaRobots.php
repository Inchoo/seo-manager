<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Ui\DataProvider\Form\Product\Modifier;

class MetaRobots implements \Magento\Ui\DataProvider\Modifier\ModifierInterface
{
    /**
     * @param \Inchoo\Seo\Model\Source\RobotsMetaTag $metaRobotsSource
     * @param \Magento\Framework\Stdlib\ArrayManager $arrayManager
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Source\RobotsMetaTag $metaRobotsSource,
        protected \Magento\Framework\Stdlib\ArrayManager $arrayManager
    ) {
    }

    /**
     * Modify data.
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * Modify meta_robots form input to select.
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        if ($metaRobotsPath = $this->arrayManager->findPath('meta_robots', $meta)) {
            $config = [
                'dataType' => 'select',
                'formElement' => 'select',
                'options' => $this->metaRobotsSource->toOptionArray()
            ];

            $meta = $this->arrayManager->merge("{$metaRobotsPath}/arguments/data/config", $meta, $config);
        }

        return $meta;
    }
}
