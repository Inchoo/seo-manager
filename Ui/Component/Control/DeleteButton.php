<?php
/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

declare(strict_types=1);

namespace Inchoo\Seo\Ui\Component\Control;

class DeleteButton extends \Inchoo\Seo\Ui\Component\Control\AbstractButton
{
    /**
     * Retrieve button-specified settings.
     *
     * @return array
     */
    public function getButtonData()
    {
        $data = [];

        if ($id = $this->request->getParam('id')) {
            $message = __('Are you sure you want to delete this record?');
            $url = $this->urlBuilder->getUrl('*/*/delete');
            $data = "{data: {id: {$id}}}";

            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => sprintf("deleteConfirm('%s', '%s', %s)", $message, $url, $data)
            ];
        }

        return $data;
    }
}
