<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Ui\Component\Listing\Column;

class Attributes extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var array
     */
    protected array $mapAttributeIdLabel = [];

    /**
     * @param \Magento\Catalog\Api\ProductAttributeRepositoryInterface $productAttributeRepository
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        protected \Magento\Catalog\Api\ProductAttributeRepositoryInterface $productAttributeRepository,
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
    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $fieldName = $this->getName();
        foreach ($dataSource['data']['items'] as &$item) {
            if ($fieldValue = $item[$fieldName]) {
                $item[$fieldName] = $this->convertAttributeIds($fieldValue);
            }
        }

        return $dataSource;
    }

    /**
     * Prepare the attribute labels.
     *
     * @param string $attributeIds
     * @return string
     */
    protected function convertAttributeIds(string $attributeIds): string
    {
        $labels = [];
        foreach (explode(',', $attributeIds) as $attributeId) {
            if ($label = $this->getAttributeLabelById((int)$attributeId)) {
                $labels[] = $label;
            }
        }

        return implode(', ', $labels);
    }

    /**
     * Get the attribute label.
     *
     * @param int $attributeId
     * @return string|null
     */
    protected function getAttributeLabelById(int $attributeId): ?string
    {
        if (!isset($this->mapAttributeIdLabel[$attributeId])) {
            try {
                $attribute = $this->productAttributeRepository->get($attributeId);
                $this->mapAttributeIdLabel[$attributeId] = $attribute->getDefaultFrontendLabel();
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                return null;
            }
        }

        return $this->mapAttributeIdLabel[$attributeId] ?? null;
    }
}
