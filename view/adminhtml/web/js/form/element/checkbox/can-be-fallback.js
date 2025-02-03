/**
 * Copyright © Inchoo d.o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author Inchoo (https://inchoo.net)
 */

define([
    'Magento_Ui/js/form/element/single-checkbox'
], function (Checkbox) {
    'use strict';

    return Checkbox.extend({
        defaults: {
            categoryId: ko.observable(),
            imports: {
                categoryId: '${ $.provider }:data.category_id'
            }
        },

        initialize: function () {
            this._super();

            this.disabled(!this.categoryId())

            this.attributeRows.subscribe(function (categoryId) {
                this.disabled(!categoryId);
            }, this);

            return this;
        }
    });
});
