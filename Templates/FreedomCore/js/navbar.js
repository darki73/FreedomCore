window.nav = window.nav || {};

nav.login = function(){
    $(".dropdown.open").removeClass("open");
    return Login.open();
};
nav.login.init = function(){
    //$('#nav-client-bar a[href="?login"]').on("click.nav.login",nav.login);
};

nav.init = function(){
    nav.login.init();
};

$(nav.init);


// sample test data, delete when dev complete
var testStatus = {
    total: 9,
    details: []
}

window.nav = window.nav || {};
window.nav.tickets = window.nav.tickets || {};


/**
 * Gets and displays unread support tickets.
 * Simplified from common version
 */
nav.tickets.initialize = function(selector) {
    if(Core.loggedIn) {
        this.self = this;
        this.counters = $(selector);
        this.ajaxSettings = {
            timeout: 3000,
            url: Core.secureSupportUrl + 'update/json',
            ifModified: true,
            global: false,
            dataType: 'jsonp',
            jsonpCallback: 'getStatus',
            contentType: "application/json; charset=utf-8",
            crossDomain: true,
            cache: false,
            data: {
                supportToken: window.supportToken
            }
        }
        this.loadStatus();
    }
}

nav.tickets.loadStatus = function(callback) {
    if(this.counters.length) {
        var _this = this,
            response = this.getUpdates(),
            callback = callback || this.handleResponse;
        response.done(function(json, status) {
            _this.handleResponse.call(_this, json, status);
        });
    }
}

nav.tickets.handleResponse = function(json, status) {
    if ("notmodified" !== status) {

        // todo: replace with actual json.total, instead of test data
        this.updateTotal(json.total);
    }
}

nav.tickets.getUpdates = function() {
    return $.ajax(this.ajaxSettings);
}

nav.tickets.updateTotal = function(count) {
    count = (typeof count === 'number') ? count : 0;
    this.counters
        .text(count)
        [(count > 0) ? "removeClass" : "addClass"]('no-updates');
}

$(function(){
    nav.tickets.initialize(".nav-support-ticket-counter");
});
$(function(){
    // adding stopPropagation breaks GTM for ie8 only...
    if (document.addEventListener) {
        // dont allow clicks on dropdown to trigger a close on the menu.
        $('#nav-client-bar .dropdown-menu, #nav-client-footer .dropdown-menu').on('click', function(e) {e.stopPropagation()});
    }

});
/**
 * Helper functions for switching language / region.
 * Modified from Locale.js in common
 */

window.nav = window.nav || {};
window.nav.locale = {
    activeRegion: null,
    activeTarget: null,
    activeLanguage: null,

    init: function(selector) {
        this.container = $(selector);

        this.container.on('click', 'a.select-region', $.proxy(this.changeRegion, this));
        this.container.on('click', 'a.select-language', $.proxy(this.changeLanguage, this));

        this.activeRegion = this.container.find('#select-regions .active');
        this.activeLanguage = this.container.find('#select-language .active').find('li');
        this.activeLanguageGroup = this.container.find('#select-language .active');
        this.currentRegion = this.container.find('#select-regions .current');
        this.currentLanguage = this.container.find('#select-language .current').find('li.current');
        this.btn = $('.nav-lang-change');
        this.btn.addClass('disabled');
    },

    disableSelection: function() {
        this.btn.addClass('disabled');
        this.activeRegion.removeClass('active');
        this.activeLanguage.removeClass('active');
        this.activeLanguageGroup.removeClass('active');
    },

    changeRegion: function(e) {
        e.preventDefault();
        e.stopPropagation();

        var $target = $(e.target);

        // disable all active state items
        this.disableSelection();
        // remove any href the Change button may have
        this.btn.attr('href', 'javascript:;');

        // get active region
        this.activeRegion = $target.parent();
        this.activeLanguageGroup = $target.parents('.nav-international-container').find("[data-region='" + $target.attr('data-target') + "']");
        var languages = this.activeLanguageGroup.find("li");

        // set active
        this.activeLanguageGroup.addClass('active');
        this.activeRegion.addClass('active');

        // if changed back to current region and user has not chosen a language, make current language active
        if (this.activeRegion.hasClass('current') && (languages.find('active').length === 0)) {
            this.currentLanguage.addClass('active');
        }

        // if region has only 1 language, make that language pre-selected
        if (languages.length === 1) {
            languages.addClass("active");
            this.btn.removeClass('disabled');
            this.btn.attr('href', languages.find("a").attr("href"));
        }
    },

    changeLanguage: function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $target = $(e.target);
        var href = $target.attr('href');

        // disable previous language
        this.activeLanguage.removeClass('active');
        this.currentLanguage.removeClass('active');

        // reset button
        this.btn.addClass('disabled');
        this.btn.attr('href', 'javascript:;');

        // get active language
        this.activeLanguage = $target.parent();
        this.activeLanguage.addClass('active');

        // only enable button if active class is different from current class
        if (!this.activeLanguage.hasClass('current')) {
            this.btn.attr('href', href);
            this.btn.removeClass('disabled');
        }
    }
};

nav.collapsible = nav.collapsible || {};
nav.collapsible.initialize = function() {
    var _this = this;
    $("[data-toggle='nav-collapse']").on('click', _this.toggle);
};

nav.collapsible.toggle = function(e) {
    e.preventDefault();
    var target = $(this).attr('data-target');
    $(target).toggleClass('in');
    $(target).prev().toggleClass('open');
};

$(function(){
    nav.locale.init('.nav-international-container');
    nav.collapsible.initialize();
});


var Navbar = Navbar || {};
Navbar.menu_out = false;
Navbar.close = function() {
    $('.nav-mobile-menu-wrap').removeClass('out');
    this.menu_out = false;
}


$(function(){
    var $navMobileWrap = $('.nav-mobile-menu-wrap');
    var $navMobileWrapLeft = $navMobileWrap.filter('.left');
    var $navMobileWrapRight = $navMobileWrap.filter('.right');
    var $blackout = $('.nav-client #blackout');
    var toggleMobileNav = function toggleMobileNav(side) {
        if (side === 'right') {
            $navMobileWrapLeft.removeClass('out');
            $navMobileWrapRight.addClass('out');
        } else if (side === 'left') {
            $navMobileWrapRight.removeClass('out');
            $navMobileWrapLeft.addClass('out');
        }

        Navbar.menu_out = true;
        return Navbar.menu_out;
    }
    $('.nav-remove-icon').on('click', function(e) {
        Navbar.close();
    });
    //right side menu
    $('.nav-global-menu-icon').on('click', function(e) {
        toggleMobileNav('right');
    });
    // left side menu
    $('.nav-hamburger-menu-icon').on('click', function(e) {
        toggleMobileNav('left');
    });
    // clicking on blackout while menu is out will close the menu
    $blackout.on('click', function(e) {
        if (Navbar.menu_out) {
            Navbar.close();
        }
    });
});

window.nav = window.nav || {};
window.nav.modals = {
    euCookieComplianceAgreed: null,

    init: function(selector) {
        this.container = $(selector);
        this.euCookieComplianceAgreed = Cookie.read('eu-cookie-compliance-agreed');
        if (!this.euCookieComplianceAgreed) {
            this.container.removeClass('hide');
            Cookie.create('eu-cookie-compliance-agreed', 1, {expires: 365 * 24, path: '/'}); //cookie is created once user has seen the disclaimer
            this.container.on('click', '#cookie-compliance-close', $.proxy(this.closeCookieModal, this));
            this.container.on('click', '#cookie-compliance-agree', $.proxy(this.closeCookieModal, this));
        }
    },

    closeCookieModal: function() {
        $('.eu-cookie-compliance.desktop').addClass('hide');
        $('.eu-cookie-compliance.mobile').addClass('hide');
        Cookie.create('eu-cookie-compliance-agreed', 1, {expires: 365 * 24, path: '/'});
    }
};

$(function() {
    nav.modals.init('.eu-cookie-compliance');
});