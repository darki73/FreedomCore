$(document).ready(function() {
    Services.initialize();
});

/**
 * Toggles display of WoW service categories, e.g., Character Services, Additional Services.
 *
 * @copyright   2010, Blizzard Entertainment, Inc.
 * @class       Services
 * @requires
 * @example
 *
 *      Services.initialize();
 *
 */
var Services = {
    wrapper: {},
    menuItems: {},
    initialize: function() {
        Services.wrapper = $('.service-selection');
        Services.menuItems = $('.wow-services li a');
        Services.total = Services.menuItems.length;

        if (!Services.menuItems.length) {
            return false;
        }

        Services.menuItems.bind({
            'click': function() {
                Services.pick(this);
                return false;
            }
        });
    },
    clear: function() {
        if (!Services.wrapper.length) {
            Services.initialize();
        }

        Services.wrapper.removeClass().addClass('service-selection');
    },
    /**
     * @param target Selected .wow-services li a element
     */
    pick: function(target) {
        if (!Services.wrapper.length) {
            Services.initialize();
        }
        var category = $(target).attr('href').replace('#','');

        Services.clear();
        Services.wrapper.addClass(category);
        var positions = [91, 338, 585, 832];
        var position = 91;
        for ( var index = 0; index < positions.length; index++ ) {
            if (Services.menuItems[index].className === category) {
                position = positions[index];
                break;
            }
        }
        $('.position').animate({
            left: position
        }, 400);
    }
};

var SorDialog = {
    show: function(cancelLink, onclick, confirmLink) {
        $('#sor-unclaimed-dialog').dialog('open');
        $('#sor-unclaimed-dialog .ui-button').attr('href', confirmLink);
        $("#sor-unclaimed-dialog .ui-cancel").attr("href", cancelLink).attr("onclick", onclick);
        $("#sor-unclaimed-dialog .ui-cancel").bind({
            'click': function() {
                $('#sor-unclaimed-dialog').dialog('close');
            }
        });
    }
};