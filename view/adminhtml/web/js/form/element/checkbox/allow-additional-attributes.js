/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

define([
    'ko',
    'Magento_Ui/js/form/element/single-checkbox'
], function (ko, Checkbox) {
    'use strict';

    return Checkbox.extend({
        defaults: {
            attributeRows: ko.observableArray(),
            imports: {
                attributeRows: '${ $.provider }:data.attribute_rows'
            }
        },

        initialize: function () {
            this._super();

            this.disabled(this.attributeRows().length === 0)

            this.attributeRows.subscribe(function (attributeRows) {
                this.disabled(attributeRows.length === 0);
            }, this);

            return this;
        }
    });
});
