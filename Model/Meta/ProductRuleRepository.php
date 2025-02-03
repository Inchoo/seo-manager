<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Meta;

class ProductRuleRepository
{
    /**
     * @var \Inchoo\Seo\Model\Meta\ProductRule[]
     */
    private array $cacheById = [];

    /**
     * @param \Inchoo\Seo\Model\Meta\ProductRuleFactory $productRuleFactory
     * @param \Inchoo\Seo\Model\ResourceModel\Meta\ProductRule $productRuleResource
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Meta\ProductRuleFactory $productRuleFactory,
        protected \Inchoo\Seo\Model\ResourceModel\Meta\ProductRule $productRuleResource
    ) {
    }

    /**
     * Get product meta rule by ID.
     *
     * @param int $id
     * @param bool $reload
     * @return \Inchoo\Seo\Model\Meta\ProductRule
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id, bool $reload = false): \Inchoo\Seo\Model\Meta\ProductRule
    {
        if (isset($this->cacheById[$id]) && !$reload) {
            return $this->cacheById[$id];
        }

        $productRule = $this->productRuleFactory->create();
        $this->productRuleResource->load($productRule, $id);

        if ($productRule->getId() === null) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('The product rule with the Id: "%1" does not exist.', $id)
            );
        }

        return $this->cacheById[$id] = $productRule;
    }

    /**
     * Save product meta rule.
     *
     * @param \Inchoo\Seo\Model\Meta\ProductRule $metaRule
     * @return \Inchoo\Seo\Model\Meta\ProductRule
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Inchoo\Seo\Model\Meta\ProductRule $metaRule): \Inchoo\Seo\Model\Meta\ProductRule
    {
        try {
            $this->productRuleResource->save($metaRule);
            return $this->get((int)$metaRule->getId(), true);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not save the product rule: %1', $exception->getMessage()),
                $exception
            );
        }
    }

    /**
     * Delete product meta rule.
     *
     * @param \Inchoo\Seo\Model\Meta\ProductRule $metaRule
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Inchoo\Seo\Model\Meta\ProductRule $metaRule): bool
    {
        try {
            $this->productRuleResource->delete($metaRule);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __('Could not delete the product rule: %1.', $exception->getMessage()),
                $exception
            );
        }

        unset($this->cacheById[$metaRule->getId()]);
        return true;
    }

    /**
     * Delete product meta rule by ID.
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->get($id));
    }
}
