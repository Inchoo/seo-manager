/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

define([
    'jquery',
    'Magento_Ui/js/form/element/select'
], function ($, Select) {
    'use strict';

    return Select.extend({
        defaults: {
            fetchOptionsUrl: '',
            attributeId: null,
            optionsByAttributeId: {},
            imports: {
                attributeId: '${ $.provider }:${ $.parentScope }.attribute'
            },
            listens: {
                '${ $.provider }:${ $.parentScope }.attribute': 'attributeChanged'
            },
            links: {
                optionsByAttributeId: '${ $.provider }:data.options_by_attribute'
            },
            tracks: {
                attributeId: true,
                optionsByAttributeId: true
            }
        },

        setInitialValue: function () {
            if (this.attributeId && this.optionsByAttributeId.hasOwnProperty(this.attributeId)) {
                this.setOptions(this.optionsByAttributeId[this.attributeId]);
            }

            return this._super();
        },

        attributeChanged: function (attributeId) {
            if (this.optionsByAttributeId.hasOwnProperty(attributeId)) {
                this.setOptions(this.optionsByAttributeId[attributeId]);
            } else if (attributeId) {
                this.fetchOptions(attributeId);
            } else {
                this.setOptions([]);
            }

            return this;
        },

        fetchOptions: function (attributeId, showLoader = true) {
            let request = $.ajax({
                url: this.fetchOptionsUrl,
                type: 'GET',
                data: {
                    attribute_id: attributeId,
                },
                showLoader: showLoader
            });

            request.done((response) => {
                let optionsByAttributeId = this.optionsByAttributeId;
                optionsByAttributeId[attributeId] = response;
                this.optionsByAttributeId = optionsByAttributeId;
                this.setOptions(response);
            });

            request.fail((jqXHR, textStatus, errorThrown) => {
                console.error('Error:', errorThrown);
            });

            return request;
        }
    });
});
