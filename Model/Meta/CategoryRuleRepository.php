<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Meta;

class CategoryRuleRepository
{
    /**
     * @var \Inchoo\Seo\Model\Meta\CategoryRule[]
     */
    private array $cacheById = [];

    /**
     * @param \Inchoo\Seo\Model\Meta\CategoryRuleFactory $categoryRuleFactory
     * @param \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule $categoryRuleResource
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Meta\CategoryRuleFactory $categoryRuleFactory,
        protected \Inchoo\Seo\Model\ResourceModel\Meta\CategoryRule $categoryRuleResource,
    ) {
    }

    /**
     * Get category meta rule by ID.
     *
     * @param int $id
     * @param bool $reload
     * @return \Inchoo\Seo\Model\Meta\CategoryRule
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id, bool $reload = false): \Inchoo\Seo\Model\Meta\CategoryRule
    {
        if (isset($this->cacheById[$id]) && !$reload) {
            return $this->cacheById[$id];
        }

        $categoryRule = $this->categoryRuleFactory->create();
        $this->categoryRuleResource->load($categoryRule, $id);

        if ($categoryRule->getId() === null) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('The category rule with the Id: "%1" does not exist.', $id)
            );
        }

        return $this->cacheById[$id] = $categoryRule;
    }

    /**
     * Save category meta rule.
     *
     * @param \Inchoo\Seo\Model\Meta\CategoryRule $metaRule
     * @return \Inchoo\Seo\Model\Meta\CategoryRule
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function save(\Inchoo\Seo\Model\Meta\CategoryRule $metaRule): \Inchoo\Seo\Model\Meta\CategoryRule
    {
        try {
            $this->categoryRuleResource->save($metaRule);
            return $this->get((int)$metaRule->getId(), true);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __('Could not save the category rule: %1', $exception->getMessage()),
                $exception
            );
        }
    }

    /**
     * Delete category meta rule.
     *
     * @param \Inchoo\Seo\Model\Meta\CategoryRule $metaRule
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Inchoo\Seo\Model\Meta\CategoryRule $metaRule): bool
    {
        try {
            $this->categoryRuleResource->delete($metaRule);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __('Could not delete the category rule: %1.', $exception->getMessage()),
                $exception
            );
        }

        unset($this->cacheById[$metaRule->getId()]);
        return true;
    }

    /**
     * Delete category meta rule by ID.
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
