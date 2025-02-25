<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Modifier\Filter\PlaceholderResolver;

class Category implements \Inchoo\Seo\Model\Filter\PlaceholderResolverInterface
{
    /**
     * @var array
     */
    private array $requestVarLabelMap = [];

    /**
     * @param \Inchoo\Seo\Model\Layer\State $layerState
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Layer\State $layerState
    ) {
    }

    /**
     * Get the placeholder value.
     *
     * @param string $placeholder
     * @param \Magento\Catalog\Model\Category|\Magento\Framework\DataObject $model
     * @return string
     */
    public function getValue(string $placeholder, \Magento\Framework\DataObject $model): string
    {
        try {
            switch ($placeholder) {
                case 'website':
                    return (string)$model->getStore()->getWebsite()->getName();
                case 'store':
                    return (string)$model->getStore()->getGroup()->getName();
                case 'store_view':
                    return (string)$model->getStore()->getName();
                case 'category':
                case 'name':
                    return (string)$model->getName();
                case 'parent_category':
                case 'parent_name':
                    return $this->getParentName($model->getParentCategory());
                case 'parent_category_root':
                case 'parent_name_root':
                    return $this->getParentName($model->getParentCategory(), true);
            }

            // 'grandparent_name', 'grandparent_name_root', 'grandgrandparent_name', 'grandgrandgrandparent_name'
            if (str_contains($placeholder, 'grandparent_name') || str_contains($placeholder, 'grandparent_category')) {
                $grandParentLevel = max(0, $model->getLevel() - substr_count($placeholder, 'grand') - 1);

                return $this->getGrandParentName(
                    $model->getParentCategory(),
                    $grandParentLevel,
                    str_contains($placeholder, '_root') // allow root
                );
            }

            return (string)$this->getFilterItemLabelByRequestVar($placeholder) ?: '';
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $value = '';
        }

        return (string)$value;
    }

    /**
     * Get the filter item label.
     *
     * @param string $requestVar
     * @return string|null
     */
    protected function getFilterItemLabelByRequestVar(string $requestVar): ?string
    {
        if (!$this->requestVarLabelMap) {
            foreach ($this->layerState->getAllFilterItems() as $filterItem) {
                try {
                    $filter = $filterItem->getFilter();
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    continue;
                }
                $this->requestVarLabelMap[$filter->getRequestVar()] = $filterItem->getLabel();
            }
        }

        return $this->requestVarLabelMap[$requestVar] ?? null;
    }

    /**
     * Retrieve the name of the parent.
     *
     * @param \Magento\Catalog\Model\Category|\Magento\Framework\DataObject $parentModel
     * @param bool $allowRoot
     * @return string
     */
    protected function getParentName(\Magento\Framework\DataObject $parentModel, bool $allowRoot = false): string
    {
        $minLevel = $allowRoot ? 1 : 2;
        if ((int)$parentModel->getLevel() < $minLevel) {
            return ''; // exclude root categories
        }
        return (string)$parentModel->getName();
    }

    /**
     * Retrieve the name of the grandparent.
     *
     * @param \Magento\Catalog\Model\Category|\Magento\Framework\DataObject $parentModel
     * @param int $level
     * @param bool $allowRoot
     * @return string
     */
    protected function getGrandParentName(
        \Magento\Framework\DataObject $parentModel,
        int $level,
        bool $allowRoot = false
    ): string {
        $grandParent = $parentModel->getParentCategory();
        $grandParentLevel = (int)$grandParent->getLevel();

        $minLevel = $allowRoot ? 1 : 2;
        if ($grandParentLevel < $minLevel) {
            return ''; // exclude root categories
        }

        if ($grandParentLevel > $level) {
            return $this->getGrandParentName($grandParent, $level, $allowRoot);
        }

        return (string)$grandParent->getName();
    }
}
