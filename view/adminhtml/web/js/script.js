define([
    'underscore',
    'jquery',
    'jquery/ui'
], function(_, $) {
    'use strict';
    // widget unique name - componentBasicTitle
    $.widget('mage.componentBasicTitle', {
        newLabelHtml: '<label><img src="https://www.commersys.com/wp-content/uploads/company_logo/logo.png" alt="Commersys" height="25" style="vertical-align:middle;"/></label>',

        _create: function () {
            this.elementTitle = $(this.element[0]);
            var title = this.elementTitle.text(),
                menuItems = $(this.options.menuItemSelector);

            _.forEach(menuItems, function (item) {
                var element = $(item);

                if(this.options.addLabelTo === $('strong', element).text()){
                    $(element).children('strong').hide();
                    $(element).append($(this.newLabelHtml));
                }
            }, this);
        }
    });

    return $.mage.componentBasicTitle;
});