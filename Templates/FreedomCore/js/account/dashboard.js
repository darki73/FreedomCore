$(document).ready(function() {
    DashboardLinks.disable();
    //UpgradeBox.initialize();
    AccountHistory.initialize();
    DashboardForm.initialize();
});

/**
 * Kills disabled links.
 *
 * @copyright   2011, Blizzard Entertainment, Inc.
 * @class       Services
 * @example
 *
 *      DashboardLinks.disable();
 *
 */
var DashboardLinks = {
    disable: function() {
        DashboardLinks.disabled = $('a.disabled');

        if (!DashboardLinks.disabled.length) {
            return false;
        }

        DashboardLinks.disabled.bind({
            'click': function() {
                return false;
            }
        });
    },
    toggleHelp: function(toggle) {
        var target = $(toggle).attr('rel');
        if ($('#' + target).css('display') === 'none') {
            $('#' + target).show();
        } else {
            $('#' + target).hide();
        }
    }
};

/**
 * Displays animation for game upgrades.
 *
 * @copyright   2011, Blizzard Entertainment, Inc
 * @class       UpgradeBox
 */
var UpgradeBox = Class.extend({

    /**
     * jQuery objects for specific elements.
     */
    container: null,
    box: null,
    swf: null,

    /**
     * Configuration.
     */
    config: {},

    /**
     * Initialize the class and apply the config.
     */
    init: function(container, config) {

        container = $(container);
        config = typeof config !== 'undefined' ? config : {};

        if (container.length) {

            if (container[0].tagName.toLowerCase() !== 'img') {
                box = container.find('img');
            } else {
                box = container;
            }

            this.box = (box.length) ? box : null;

        }

        if (box !== null) {

            // Merge configuration
            this.config = $.extend({}, config);

            // Setup the class
            this.setup();

        }

    },

    setup: function() {

        this.swf = Core.staticUrl + '/flash/management/ding.swf';

        $.ajax({
            url: this.swf,
            context: this,
            complete: function() {
                this.reveal();
            }
        });
    },

    reveal: function() {
        var box = this.box,
            flashvars = {},
            params = {
                menu: 'false',
                quality: 'high',
                wmode: 'transparent',
                allowfullscreen: "false"
            },
            attributes = {};

        // Play the animation.
        if (!(jQuery.browser.msie && (jQuery.browser.version == '9.0' || jQuery.browser.version == '10.0')) && !jQuery.browser.webkit) {
            swfobject.embedSWF(this.swf, 'ding', '542', '410', '9.0.0', false, flashvars, params, attributes);
        }

        // for ie png bugs
        var fadeTime = 750;
        if ($.browser.msie){
            fadeTime = 0;
        }
        // Reveal the box.
        setTimeout(function() {
            box.fadeIn(fadeTime);
        }, 250);

        // Get rid of the SWF and show the badges.
        setTimeout(function() {
            $('#ding').remove();
            $('.active-trial').fadeIn(fadeTime);
            $('.trial-time-remaining').fadeIn(fadeTime);
            $('.upgrade-available').fadeIn(fadeTime);
            $('.upgrade-pending').fadeIn(fadeTime);
        }, 3000);

    }

});

/**
 * Toggles display of previous Product Levels, including Standard or Collector's Edition status.
 *
 * @copyright   2011, Blizzard Entertainment, Inc.
 * @class       AccountHistory
 * @requires
 * @example
 *
 *      AccountHistory.initialize();
 *
 */
var AccountHistory = {
    primaryAccount: {},
    secondaryAccounts: '',
    initialize: function() {
        AccountHistory.primaryAccount = $('.account-history');
        AccountHistory.secondaryAccounts = $('.secondary-account');

        if (!AccountHistory.primaryAccount.length) {
            return false;
        }

        AccountHistory.primaryAccount.bind({
            'click': function() {
                if (AccountHistory.primaryAccount.hasClass('expanded')) {
                    AccountHistory.collapse();
                } else {
                    AccountHistory.expand();
                }
                return false;
            }
        });
    },
    expand: function() {
        if (!AccountHistory.primaryAccount.length) {
            AccountHistory.initialize();
        }

        AccountHistory.primaryAccount.addClass('expanded');
        AccountHistory.secondaryAccounts.show();
    },
    collapse: function() {
        if (!AccountHistory.primaryAccount.length) {
            AccountHistory.initialize();
        }

        AccountHistory.primaryAccount.removeClass('expanded');
        AccountHistory.secondaryAccounts.hide();
    }
};

/**
 * Toggles display of inline dashboard form.
 *
 * @copyright   2011, Blizzard Entertainment, Inc.
 * @class       AccountHistory
 * @requires
 * @example
 *
 *      DashboardForm.initialize();
 *
 */
var DashboardForm = {
    accountWrapper: $('.account-management'),
    formWrapper: $('.dashboard-form'),
    upgradeWrapper: $('.upgrade-option'),
    submitButtons: $('.dashboard-form .ui-button'),
    activeForm: {},
    initialize: function() {
        if (!DashboardForm.accountWrapper.length || !DashboardForm.formWrapper.length ) {
            return false;
        }

        if (DashboardForm.submitButtons.length) {
            UI.freezeButton($(DashboardForm.submitButtons));
            if ($('#soft-world').length) {
                UI.wakeButton($('#soft-world'));
                $('#soft-world').attr('target','_blank');
            }
            if ($('#choose-sub-type-submit').length) {
                UI.wakeButton($('#choose-sub-type-submit'));
            }
        }

        var length = DashboardForm.formWrapper.length;
        var i = 0;
        for (i; i < length; i++) {
            DashboardForm.styleInputs(DashboardForm.formWrapper[i]);
        }

        if (window.location.hash) {
            var hash = window.location.hash;
            if (typeof hash === 'string' && hash.indexOf('#') === 0 && hash.length < 30) {
                switch (hash) {
                    case '#form=subscription-option':
                        DashboardForm.show($('#subscription-option'));
                        break;
                }
            }
        }
    },
    styleInputs: function(form) {
        var payOptions = $(form).children('form').children('.pay-options').children('label');
        var codeInput = $(form).children('form').children('.simple-input').children('.input');
        $(payOptions).bind({
            'click': function() {
                if (!$(this).hasClass('disabled')) {
                    var product = $(this).attr('for').split('-')[0],
                        productId = $(this).find('input').val();
                    $('#product').val(product);
                    $('#productId').val(productId);
                    $(payOptions).removeClass('selected');
                    $(this).addClass('selected');
                    $(this).find('input:radio').attr("checked", "checked");
                    UI.wakeButton($(this).parent().parent().children('.ui-controls').children('.ui-button'));
                } else {
                    return false;
                }
            },
            'mouseover': function() {
                $(this).addClass('hover');
            },
            'mouseout': function() {
                $(this).removeClass('hover');
            }
        });
        $(codeInput).bind({
            'keyup': function() {
                var submitButton = $(this).parent().parent().children('.simple-input').children('.ui-button');
                if ($(this).val().length > 0) {
                    setTimeout(function() { UI.wakeButton($(submitButton)); }, 250);
                } else {
                    UI.freezeButton($(submitButton));
                }
            },
            'blur': function() {
                var submitButton = $(this).parent().parent().children('.simple-input').children('.ui-button');
                if ($(this).val().length > 0) {
                    UI.wakeButton($(submitButton));
                } else {
                    UI.freezeButton($(submitButton));
                }
            }
        });
    },
    show: function(form, url, region) {
        DashboardForm.activeForm = form;
        if (!DashboardForm.activeForm.length) {
            return false;
        }
        DashboardForm.activeForm.children('iframe').attr('src', url);
        if (region == "KR") {
            DashboardForm.accountWrapper.fadeOut(250, function() {
                $(this).css('display', 'none');
                DashboardForm.activeForm.fadeIn(250);
            });
        }
        else {
            DashboardForm.upgradeWrapper.fadeOut(250);
            DashboardForm.accountWrapper.fadeOut(250, function() {
                $(this).css('display', 'none');
                DashboardForm.activeForm.fadeIn(250);
            });
        }
    },
    hide: function(form) {
        DashboardForm.activeForm = form;

        if (!DashboardForm.activeForm.length) {
            return false;
        }

        // setting to javascript: false; to prevent mixed content warning in IE
        DashboardForm.activeForm.fadeOut(250, function() {
            $(this).css('display', 'none');
            DashboardForm.activeForm.children('iframe').attr('src', 'javascript: false;');
            DashboardForm.accountWrapper.fadeIn(250);
            DashboardForm.upgradeWrapper.fadeIn(250);
        });
    },
    swap: function(form1,form2,url) {
        DashboardForm.activeForm = form1;

        if (!DashboardForm.activeForm.length) {
            return false;
        }

        // setting to javascript: false; to prevent mixed content warning in IE
        DashboardForm.activeForm.fadeOut(250, function() {
            $(this).css('display', 'none');
            DashboardForm.activeForm.children('iframe').attr('src', 'javascript: false;');
            DashboardForm.show(form2,url);
        });
    },
    toggleSubType: function() {
        var subType = $('#choose-sub-type input:checked').attr('id');
        if (subType == 'choose-sub') {
            document.location.href = $('#choose-sub-type form').attr('action');
        } else if (subType == 'choose-prepaid') {
            DashboardForm.swap($('#choose-sub-type'), $('#add-game-time'), '');
        } else if (subType == 'choose-game-time') {
            DashboardForm.swap($('#choose-sub-type'), $('#subscription-option'), '');
        }
    }
};

/**
 * Handler for RPC calls (inline forms).
 *
 * @copyright   2011, Blizzard Entertainment, Inc.
 * @class       Services
 * @requires
 * @example
 *
 *      RPC.cancel('#form-id');
 *
 */
var RPC = {
    cancel: function(form) {
        DashboardForm.hide($(form));
        $('.alert').fadeOut(250, function() { $(this).remove(); })
    },
    displayAlert: function(message) {
        $('.alert').remove();
        $('.dashboard').before(message);
    },
    reloadPage: function() {
        document.location.href = document.location.href;
    }
};

/**
 * Handles Account creation tracking
 * - extended by SC2 and WOW dashboards
 */
var AdTracking = {
    a: (Math.random() + '') * 10000000000000,
    _createFragment: function (url) {
        var trackingObject = document.createElement((Core.isIE(6)) ? 'iframe' : 'object');

        trackingObject.width = 0;
        trackingObject.height = 0;
        if (Core.isIE(6)) {
            trackingObject.src = url;
        } else {
            trackingObject.type = 'text/html';
            trackingObject.data = url;
        }
        document.getElementsByTagName('body')[0].appendChild(trackingObject);
    }
};