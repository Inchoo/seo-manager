<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Ui\Component\Listing\Column;

class Category extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        protected \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
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
            $categoryId = $item[$fieldName];
            if ($categoryId && $categoryName = $this->getCategoryNameById((int)$categoryId)) {
                $item[$fieldName] = $categoryName;
            }
        }

        return $dataSource;
    }

    /**
     * Get the category name.
     *
     * @param int $categoryId
     * @return string|null
     */
    protected function getCategoryNameById(int $categoryId): ?string
    {
        try {
            return $this->categoryRepository->get($categoryId)->getName();
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }
    }
}
