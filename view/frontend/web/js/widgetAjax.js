/**
 * Copyright © MageKey. All rights reserved.
 */
define([
    'jquery',
    'mage/apply/main'
], function($, main) {
    "use strict";

    $.widget('magekey.widgetAjax', {

        options: {
            loadUrl: null,
            hash: null
        },

        _create: function() {
            this.loadContent();
        },

        loadContent: function() {
            var self = this;
            $.ajax({
                url: self.options.loadUrl,
                type: 'GET',
                dataType: 'html',
                data: {hash: self.options.hash},
                success: function (content) {
                    self.element.html(content);
                    main.apply();
                }
            });
        }
    });

    return $.magekey.widgetAjax;
});
