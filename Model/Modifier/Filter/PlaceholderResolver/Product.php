<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Modifier\Filter\PlaceholderResolver;

class Product implements \Inchoo\Seo\Model\Filter\PlaceholderResolverInterface
{
    /**
     * @var \Magento\Catalog\Model\Category[]
     */
    private array $productIdCategoryCache = [];

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     * @param \Magento\Catalog\Api\ProductAttributeRepositoryInterface $productAttributeRepository
     */
    public function __construct(
        protected \Magento\Catalog\Model\ResourceModel\Product $productResource,
        protected \Magento\Catalog\Api\ProductAttributeRepositoryInterface $productAttributeRepository
    ) {
    }

    /**
     * Get the placeholder value.
     *
     * @param string $placeholder
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\DataObject $model
     * @return string
     */
    public function getValue(string $placeholder, \Magento\Framework\DataObject $model): string
    {
        try {
            switch ($placeholder) {
                case 'website':
                    $value = $model->getStore()->getWebsite()->getName();
                    break;
                case 'store':
                    $value = $model->getStore()->getGroup()->getName();
                    break;
                case 'store_view':
                    $value = $model->getStore()->getName();
                    break;
                case 'category':
                    $category = $this->getProductCategory($model);
                    $value = $category ? $category->getName() : '';
                    break;
                default:
                    $attribute = $this->getProductAttribute($placeholder);
                    $value = $attribute ? $attribute->getFrontend()->getValue($model) : '';
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $value = '';
        }

        return (string)$value;
    }

    /**
     * Use the category from the product or the category with the deepest level to which the product is assigned.
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Category|null
     */
    protected function getProductCategory(\Magento\Catalog\Model\Product $product): ?\Magento\Catalog\Model\Category
    {
        if ($category = $product->getCategory()) {
            return $category;
        }

        if (isset($this->productIdCategoryCache[$product->getId()])) {
            return $this->productIdCategoryCache[$product->getId()];
        }

        try {
            $collection = $this->productResource->getCategoryCollection($product);
            $collection->setStoreId($product->getStoreId());
            $collection->addAttributeToFilter('is_active', 1);
            $collection->addAttributeToSelect('name');
            $collection->setOrder('level', 'DESC');
            $collection->setPageSize(1);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return null;
        }

        if ($collection->getItems()) {
            $this->productIdCategoryCache[$product->getId()] = $collection->getFirstItem();
        }

        return $this->productIdCategoryCache[$product->getId()] ?? null;
    }

    /**
     * Get the product attribute.
     *
     * @param string $attributeCode
     * @return \Magento\Catalog\Api\Data\ProductAttributeInterface|null
     */
    protected function getProductAttribute(string $attributeCode): ?\Magento\Catalog\Api\Data\ProductAttributeInterface
    {
        try {
            return $this->productAttributeRepository->get($attributeCode);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }
    }
}
