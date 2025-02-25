<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Model\Meta;

class CustomRobotsRepository
{
    /**
     * @param \Inchoo\Seo\Model\Meta\CustomRobotsFactory $customRobotsFactory
     * @param \Inchoo\Seo\Model\ResourceModel\Meta\CustomRobots $customRobotsResource
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Meta\CustomRobotsFactory $customRobotsFactory,
        protected \Inchoo\Seo\Model\ResourceModel\Meta\CustomRobots $customRobotsResource,
    ) {
    }

    /**
     * Get custom meta robots by ID.
     *
     * @param int $entityId
     * @return \Inchoo\Seo\Model\Meta\CustomRobots
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $entityId): \Inchoo\Seo\Model\Meta\CustomRobots
    {
        $object = $this->customRobotsFactory->create();
        $this->customRobotsResource->load($object, $entityId);
        if (!$object->getEntityId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('The meta robots entity with the Id: "%1" does not exist.', $entityId)
            );
        }

        return $object;
    }

    /**
     * Save custom meta robots.
     *
     * @param \Inchoo\Seo\Model\Meta\CustomRobots $object
     * @return \Inchoo\Seo\Model\Meta\CustomRobots
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Inchoo\Seo\Model\Meta\CustomRobots $object): \Inchoo\Seo\Model\Meta\CustomRobots
    {
        try {
            $this->customRobotsResource->save($object);
        } catch (\Magento\Framework\Exception\AlreadyExistsException $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('A custom meta robots with the same URL path for the selected store view already exists.',)
            );
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Meta robots could not be saved: %1', $e->getMessage())
            );
        }

        return $object;
    }

    /**
     * Delete custom meta robots.
     *
     * @param \Inchoo\Seo\Model\Meta\CustomRobots $object
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Inchoo\Seo\Model\Meta\CustomRobots $object): bool
    {
        try {
            $this->customRobotsResource->delete($object);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __('Meta robots could not be deleted: %1', $e->getMessage())
            );
        }

        return true;
    }

    /**
     * Delete custom meta robots by ID.
     *
     * @param int $entityId
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById(int $entityId): bool
    {
        return $this->delete($this->get($entityId));
    }
}
