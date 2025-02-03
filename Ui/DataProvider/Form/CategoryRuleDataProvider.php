<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Ui\DataProvider\Form;

class CategoryRuleDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected array $loadedData = [];

    /**
     * @param \Inchoo\Seo\Model\Catalog\Attribute\Option\Provider $attributeOptionsProvider
     * @param \Inchoo\Seo\Model\Meta\CategoryRuleRepository $categoryRuleRepository
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\Framework\Stdlib\ArrayManager $arrayManager
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        protected \Inchoo\Seo\Model\Catalog\Attribute\Option\Provider $attributeOptionsProvider,
        protected \Inchoo\Seo\Model\Meta\CategoryRuleRepository $categoryRuleRepository,
        protected \Magento\Framework\UrlInterface $url,
        protected \Magento\Framework\App\RequestInterface $request,
        protected \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        protected \Magento\Framework\Stdlib\ArrayManager $arrayManager,
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
    public function getData()
    {
        if ($this->loadedData) {
            return $this->loadedData;
        }

        if ($data = $this->loadData()) {
            $optionsByAttributeId = [];
            foreach ($data['attribute_rows'] ?? [] as $attributeRowData) {
                $attributeId = (int)$attributeRowData['attribute'];
                $optionsByAttributeId[$attributeId] = $this->attributeOptionsProvider->get($attributeId);
            }

            $data['options_by_attribute'] = (object)$optionsByAttributeId;

            $this->loadedData[$data['entity_id'] ?? null] = $data;
            $this->dataPersistor->clear('category_meta_rule');
        }

        return $this->loadedData;
    }

    /**
     * Return meta.
     *
     * @return array
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        // init attribute options
        $optionPath = 'general/children/attribute_rows/children/record/children/option';
        $optionComponent = $this->arrayManager->get($optionPath, $meta, []);
        $optionComponent = $this->arrayManager->set('arguments/data/config', $optionComponent, [
            'fetchOptionsUrl' => $this->url->getUrl('inchooseo/attribute/options'),
        ]);

        return $this->arrayManager->set($optionPath, $meta, $optionComponent);
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
     * Load the category meta rule data.
     *
     * @return array
     */
    protected function loadData(): array
    {
        if ($data = $this->dataPersistor->get('category_meta_rule')) {
            return $data;
        }

        if ($ruleId = $this->request->getParam($this->getRequestFieldName())) {
            try {
                $rule = $this->categoryRuleRepository->get((int)$ruleId);
                $data = $rule->getData();
                $data['attribute_rows'] = $rule->getAttributeOptionsData();
                return $data;
            } catch (\Magento\Framework\Exception\NoSuchEntityException|\JsonException $e) {
                return [];
            }
        }

        return [];
    }
}
