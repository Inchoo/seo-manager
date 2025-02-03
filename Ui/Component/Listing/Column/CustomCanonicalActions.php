<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Ui\Component\Listing\Column;

class CustomCanonicalActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        protected \Magento\Framework\UrlInterface $url,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare data source.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        $dataSource = parent::prepareDataSource($dataSource);

        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $name = $this->getData('name');

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['entity_id'])) {
                continue;
            }

            $item[$name]['edit'] = [
                'href' => $this->url->getUrl('inchooseo/canonical/edit', ['id' => $item['entity_id']]),
                'label' => __('Edit')
            ];

            if (isset($item['status'])) {
                $statusLabel = $item['status'] ? 'Disable' : 'Enable';
                $item[$name]['status'] = [
                    'href' => $this->url->getUrl('inchooseo/canonical/changeStatus', ['id' => $item['entity_id']]),
                    'label' => __($statusLabel),
                    'confirm' => [
                        'message' => __('Are you sure you want to %1 this record?', lcfirst($statusLabel))
                    ]
                ];
            }

            $item[$name]['delete'] = [
                'href' => $this->url->getUrl('inchooseo/canonical/delete', ['id' => $item['entity_id']]),
                'label' => __('Delete'),
                'confirm' => [
                    'message' => __('Are you sure you want to delete this record?')
                ]
            ];
        }

        return $dataSource;
    }
}
