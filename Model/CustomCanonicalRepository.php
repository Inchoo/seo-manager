<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model;

class CustomCanonicalRepository
{
    /**
     * @param \Inchoo\Seo\Model\CustomCanonicalFactory $customCanonicalFactory
     * @param \Inchoo\Seo\Model\ResourceModel\CustomCanonical $customCanonicalResource
     */
    public function __construct(
        protected \Inchoo\Seo\Model\CustomCanonicalFactory $customCanonicalFactory,
        protected \Inchoo\Seo\Model\ResourceModel\CustomCanonical $customCanonicalResource,
    ) {
    }

    /**
     * Get custom canonical by ID.
     *
     * @param int $entityId
     * @return \Inchoo\Seo\Model\CustomCanonical
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $entityId): \Inchoo\Seo\Model\CustomCanonical
    {
        $customCanonical = $this->customCanonicalFactory->create();
        $this->customCanonicalResource->load($customCanonical, $entityId);

        if (!$customCanonical->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('The custom canonical with the Id: "%1" does not exist.', $entityId)
            );
        }

        return $customCanonical;
    }

    /**
     * Save custom canonical.
     *
     * @param \Inchoo\Seo\Model\CustomCanonical $customCanonical
     * @return \Inchoo\Seo\Model\CustomCanonical
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Inchoo\Seo\Model\CustomCanonical $customCanonical): \Inchoo\Seo\Model\CustomCanonical
    {
        try {
            $this->customCanonicalResource->save($customCanonical);
        } catch (\Magento\Framework\Exception\AlreadyExistsException $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('A custom canonical with the same request path for the selected store view already exists.',)
            );
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not save the custom canonical: %1', $e->getMessage())
            );
        }

        return $customCanonical;
    }

    /**
     * Delete custom canonical.
     *
     * @param \Inchoo\Seo\Model\CustomCanonical $customCanonical
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Inchoo\Seo\Model\CustomCanonical $customCanonical): bool
    {
        try {
            $this->customCanonicalResource->delete($customCanonical);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __('Could not delete the custom canonical: %1.', $e->getMessage())
            );
        }

        return true;
    }

    /**
     * Delete custom canonical by ID.
     *
     * @param int $entityId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $entityId): bool
    {
        return $this->delete($this->get($entityId));
    }
}
