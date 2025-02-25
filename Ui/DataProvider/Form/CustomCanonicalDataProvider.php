<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Ui\DataProvider\Form;

class CustomCanonicalDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected array $loadedData = [];

    /**
     * @param \Inchoo\Seo\Model\CustomCanonicalRepository $customCanonicalRepository
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        protected \Inchoo\Seo\Model\CustomCanonicalRepository $customCanonicalRepository,
        protected \Magento\Framework\App\RequestInterface $request,
        protected \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function getData(): array
    {
        if ($this->loadedData) {
            return $this->loadedData;
        }

        if ($data = $this->loadData()) {
            $this->loadedData[$data['entity_id'] ?? null] = $data;
            $this->dataPersistor->clear('custom_canonical');
        }

        return $this->loadedData;
    }

    /**
     * Add field filter to collection.
     *
     * @param \Magento\Framework\Api\Filter $filter
     * @return mixed
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter) // phpcs:ignore
    {
        // this is empty by design
    }

    /**
     * Load the custom canonical data.
     *
     * @return array
     */
    protected function loadData(): array
    {
        if ($data = $this->dataPersistor->get('custom_canonical')) {
            return $data;
        }

        if ($entityId = $this->request->getParam($this->getRequestFieldName())) {
            try {
                return $this->customCanonicalRepository->get((int)$entityId)->getData();
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                return [];
            }
        }

        return [];
    }
}
