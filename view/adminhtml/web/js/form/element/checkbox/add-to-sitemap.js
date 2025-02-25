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
            categoryId: ko.observable(),
            attributeRows: ko.observableArray(),
            imports: {
                categoryId: '${ $.provider }:data.category_id',
                attributeRows: '${ $.provider }:data.attribute_rows'
            }
        },

        initialize: function () {
            this._super();

            this.isAllowed = ko.computed(function () {
                return this.categoryId() && this.attributeRows().length === 1;
            }, this);

            this.isAllowed.subscribe(function (value) {
                this.disabled(!value);
            }, this);

            return this;
        }
    });
});
