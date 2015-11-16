$(document).ready(function() {
    UI.initialize();

    //temporary overriding login.open function for disable embedded login.
    //todo remove when it changed in common.
    Login.open = function() {
        window.location = "?login";
    };
});

/*
 * Array unique value
 * Extending jQueryвЂ™s $.unique() rather than extending the native JavaScript Array() via prototype.
 * Credit: http://paulirish.com/2010/duck-punching-with-jquery/
 */
(function($) {
    var _old = $.unique;
    $.unique = function(arr) {
        // do the default behavior only if we got an array of elements
        if (arr[0] && !!arr[0].nodeType) {
            return _old.apply(this,arguments);
        } else {
            // reduce the array to contain no dupes via grep/inArray
            return $.grep(arr,function(v,k) {
                return $.inArray(v,arr) === k;
            });
        }
    };
})(jQuery);

/**
 * Functionality for all custom UI elements and systems.
 *
 * @copyright   2010, Blizzard Entertainment, Inc.
 * @class       UI
 * @example
 *
 *      UI.initialize();
 *      UI.freezeButton($('#foobar'));
 *      UI.wakeButton($('#foobar'));
 *		UI.showNotes('a[rel="noteContainerID"]');
 *
 */

var UI = {

    buttons: {},

    notes: {},

    compactInputs: {},

    inlineInputs: {},

    parent: $('#content'),

    initialize: function(parent) {

        UI.parent = parent || $('#content');

        UI.buttons = $('.ui-button', UI.parent);
        UI.notes = $('.ui-note', UI.parent);
        UI.compactInputs = $('.label-compact', UI.parent).parent().children('input.input');
        UI.inlineInputs = $('input.inline-input');

        if (UI.buttons.length > 0) {
            UI._initializeButtons();
        }

        if (UI.notes.length > 0) {
            UI._initializeNotes();
        }

        if (UI.compactInputs.length > 0) {
            UI._initializeCompactInputs();
        }

        if (UI.inlineInputs.length > 0) {
            UI._initializeInlineInputs();
        }
    },

    _initializeInlineInputs: function() {
        var length = UI.inlineInputs.length;
        var i = 0;
        for (i; i < length; i++) {
            var input 		= UI.inlineInputs[i];
            $(input).bind({
                'keypress': function(e) {
                    var val = this.value;
                    if (e.keyCode == 8) {
                        if (val.length === 0) {
                            var previous = $(this).prev('input.inline-input');
                            $(previous).focus();
                        }
                    }
                },
                'keydown': function(e) {
                    // IE does not recognize backspace with keypress in a blank input box
                    if (!jQuery.support.cssFloat) {
                        var val = this.value;
                        if (e.keyCode == 8) {
                            if (val.length === 0) {
                                var previous = $(this).prev('input.inline-input');
                                $(previous).focus();
                            }
                        }
                    }
                },
                'focus': function() {
                    // need this to place the cursor at the end of the value in IE6
                    this.value = this.value;
                },
                'keyup': function(e) {
                    /**
                     * 8:		Backspace
                     * 9:		Tab
                     * 16:		Shift
                     * 17:		Ctrl
                     * 18:		Alt
                     * 19:		Pause Break
                     * 20:		Caps Lock
                     * 27:		Esc
                     * 33:		Page Up
                     * 34:		Page Down
                     * 35:		End
                     * 36:		Home
                     * 37:		Left Arrow
                     * 38:		Up Arrow
                     * 39:		Right Arrow
                     * 40:		Down Arroww
                     * 45:		Insert
                     * 46:		Delete
                     * 144:	Num Lock
                     * 145:	Scroll Lock
                     */
                    var keys = [8, 9, 16, 17, 18, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 46, 144, 145];
                    var string = keys.toString();
                    var maxlength 	= parseInt($(this).attr('maxlength'), 10);
                    var val 		= this.value;

                    if(string.indexOf(e.keyCode) == -1 && val.length === maxlength) {
                        $(this).next('input.inline-input').focus();
                    }
                }
            });
        }
    },

    _initializeCompactInputs: function() {
        var length = UI.compactInputs.length;
        var i = length - 1;
        if (i > 0) { do {
            var input = UI.compactInputs[i];
            var label =$.trim( $(input).parent().find('.label-compact strong').text());
            $(input).val(label).bind({
                'focus': function() {
                    if ($(this).val() === label) {
                        $(this).val('');
                    }
                },
                'blur': function() {
                    if ($(this).val() === '') {
                        $(this).val(label);
                    }
                }
            });
        } while (i--);}
    },

    _initializeNotes: function() {
        UI.notes.children('.note-toggler').bind({
            'click': function() {
                UI.showNotes(this);
                return false;
            }
        });
        UI.notes.children('.toggle-note').bind({
            'click': function() {
                return false;
            }
        });
        UI.notes.children('.toggle-note').children('.note').children('.close-note').bind({
            'click': function() {
                UI.hideNotes(this);
                return false;
            }
        });
        $(document).bind({
            'click': function() {
                UI.hideNotes();
            }
        });
    },

    hideNotes: function(note) {
        if (note === undefined) {
            $('.toggle-note').hide();
            $('.note-toggler').show();
        } else {
            var target = $(note).attr('rel');
            $(note).parent().parent('.toggle-note').hide();
            $('.note-toggler[rel="' + target + '"]').show();
        }
    },

    showNotes: function(note) {
        if (note === undefined) {
            $('.toggle-note').show();
            $('.note-toggler').hide();
        } else {
            var target = $(note).attr('rel');
            $(note).hide();
            $('#' + target).show();
        }
    },

    _initializeButtons: function() {
        UI.buttons.bind({
            'click': function(e) {
                var button = $(this);
                var onclick = button.attr('onclick');
                var form = button.parents('form');

                if ((this.tagName.toLowerCase() === 'button') && (onclick === '' || onclick === undefined || onclick === null) && (form.length > 0)) {
                    e.preventDefault();
                    e.stopPropagation();
                    form.submit();
                }
            },
            'mouseover': function() {
                $(this).addClass('hover');
            },
            'mouseout': function() {
                $(this).removeClass('hover');
            }
        });
    },

    freezeButton: function(target) {
        $(target).addClass('disabled');

        if ($(target)[0].tagName.toLowerCase() === 'button') {
            $(target).attr('disabled','disabled');
        }
    },

    wakeButton: function(target) {
        $(target).removeClass('disabled');

        if ($(target)[0].tagName.toLowerCase() === 'button') {
            $(target).removeAttr('disabled');
        }
    },

    processButton: function(target) {
        $(target).addClass('processing');

        if ($(target)[0].tagName.toLowerCase() === 'button') {
            $(target).attr('disabled','disabled');
        }
    },

    disableButton: function(target) {
        $(target).addClass('disabled');

        if ($(target)[0].tagName.toLowerCase() === 'button') {
            $(target).attr('disabled','disabled');
        }
    },

    enableButton: function(target) {
        $(target).removeClass('disabled').removeClass('processing');

        if ($(target)[0].tagName.toLowerCase() === 'button') {
            $(target).removeAttr('disabled');
        }
    }
};

/**
 * Functionality for user chargeback.
 *
 * @copyright   2011, Blizzard Entertainment, Inc.
 * @class       ChargebackCall
 * @example
 *
 */

var ChargebackCall = {

    target: $("#chargebackCount"),

    chargebackCount: 0,

    chargebackCookie: 'bam-chargebackCount',

    refresh: null,

    initialize: function(refresh) {
        var cookie = Cookie.read(ChargebackCall.chargebackCookie),
            region = '',
            callFlag = false,
            i,
            length;

        if (refresh) {
            this.refresh = refresh;
        }

        if (refresh || !cookie ){
            if (typeof chargebackRegionNumbers !== 'undefined') {
                chargebackRegionNumbers = $.unique(chargebackRegionNumbers);
                for (var i = 0, length = chargebackRegionNumbers.length; i < length; i++) {
                    switch (chargebackRegionNumbers[i]) {
                        case 2:
                            region = "EU";
                            callFlag = true;
                            break;
                    }

                    if (region == "NA") {
                        region = "US";
                    }

                    if (callFlag && region !== '') {
                        ChargebackCall.getChargeback(chargebackRegionNumbers[i], region);
                        callFlag = false;
                    }
                }
            }
        } else {
            ChargebackCall.chargebackCount = cookie;
            ChargebackCall.showChargeback(false);
        }
    },

    getChargeback: function(regionNumber, region) {
        var postData = { region: region };

        $.ajax({
            type: 'POST',
            timeout: 60000,
            url: '/data/wow-licenses-details.html',
            data: postData,
            dataType: 'json',
            success: function(msg) {
                ChargebackCall.chargebackCount = 0;
                for (var i=0; i< msg.length; i++) {
                    if (msg[i] !== '' && msg[i].status !== 'ERROR') {
                        ChargebackCall.chargebackCount = parseInt(ChargebackCall.chargebackCount, 10) + parseInt(msg[i].chargebackCount, 10);
                    }
                }
                ChargebackCall.showChargeback(true);
            }
        });
    },

    showChargeback: function(hasCookie) {
        if (ChargebackCall.chargebackCount > 0 ) {
            this.target.text(ChargebackCall.chargebackCount).addClass('display-bug');
        }
        ChargebackCall.setCookie(ChargebackCall.chargebackCount);
    },

    setCookie: function(chargebackCount) {
        Cookie.create(ChargebackCall.chargebackCookie,chargebackCount,{ expires: 1 });
    }
}

/**
 * Open up the account menu dropdowns.
 */
function openAccountDropdown(node, id) {
    var target = $('#'+ id +'-menu');

    if (target.is(':visible')) {
        target.hide();
        return;
    } else {
        $('.flyout-menu').hide();
    }

    //target.css(opts);
    Toggle.open(node, '', '#'+ id +'-menu');
}

function openFloatingAccountDropdown(node, id, xAdjust, yAdjust) {
    var target = $('#'+ id +'-menu');
    var parentPosition = $(node).position();

    if (target.is(':visible')) {
        target.hide();
        return;
    } else {
        target.css({ 'top': (parseInt(parentPosition.top) + yAdjust) + 'px', 'left': (parseInt(parentPosition.left) + xAdjust) + 'px' });
        $('.flyout-menu').hide();
    }

    Toggle.open(node, '', '#'+ id +'-menu');
}

function openEbankOptions(node) {
    var target = $('#balance-options');
    if (target.is(':visible')) {
        target.hide();
        return;
    } else {
        $('#balance-options').hide();
    }

    Toggle.open(node, '', '#balance-options');
}

function ComboBox(inputText, inputSelect, toggleLink) {
    var self = this,
        inputText = $(inputText),
        inputSelect = $(inputSelect),
        inputOptions = inputSelect.find('option'),
        toggleLink = $(toggleLink),
        data = [],
        filteredData = [],
        timer = null;

    self.initialize = function() {
        if (!inputSelect.length || !inputText.length)
            return false;

        var i = 0,
            length = inputOptions.length;

        for (i; i < length; i++) {
            var thisOption = $(inputOptions[i]);
            data.push([thisOption.val(), thisOption.text(), _normalize(thisOption.text())])
        }

        toggleLink.bind({
            'click': function() {
                self.filterOptions('');
                self.showSelect();
                return false;
            }
        });

        inputSelect.bind({
            'keyup': function(e) {
                if (e.which === 13) {
                    inputSelect.trigger('click');
                    return false;
                }
            },
            'click': function() {
                self.selectOption();
                return false;
            }
        });

        inputText.attr('autocomplete', 'off');

        inputText.bind({
            'keyup': function(e) {
                clearTimeout(timer);
                timer = null;
                if (e.which === 40) {
                    inputSelect.selectedIndex = 0;
                    inputSelect.find('option')[0].selected = true;
                    inputSelect.focus();
                } else {
                    timer = setTimeout(function() {
                        self.filterOptions(e.target.value);
                    }, 250);
                }
            },
            'focus': function(e) {
                e.target.select();
            }
        });

    };

    self.hideSelect = function() {
        inputSelect.hide();
        toggleLink.show();
    };

    self.showSelect = function() {
        inputSelect.show();
        toggleLink.hide();
    };

    self.filterOptions = function(text) {
        var i = 0,
            length = inputOptions.length,
            input = '',
            test = '';

        filteredData = [];

        for (i; i < length; i++) {
            test = data[i][2];
            input = _normalize(text);
            if (test.lastIndexOf(input) >= 0)
                filteredData.push(data[i]);
        }

        _appendOptions();
    };

    self.selectOption = function() {
        inputSelect.hide();
        toggleLink.show();
        inputText.val($('#' + inputSelect.attr('id') + ' option:selected').text());
    };

    function _appendOptions() {
        var i = 0,
            length = filteredData.length,
            string = '';

        if (length === 1) {
            string = '<option value="' + filteredData[0][0] + '" selected="selected">' + filteredData[0][1] + '</option>';
            inputSelect.empty();
            inputSelect.html(string);
            self.selectOption();
            return;
        } else if (length > 0) {
            for (i; i < length; i++) {
                string = string + '<option value="' + filteredData[i][0] + '">' + filteredData[i][1] + '</option>';
            }
            self.showSelect();
        } else {
            length = data.length;
            for (i; i < length; i++) {
                string = string + '<option value="' + data[i][0] + '">' + data[i][1] + '</option>';
            }
            self.hideSelect();
        }

        inputSelect.empty();
        inputSelect.html(string);
        //Always keep inputSelect width the same with inputText to fix a width bug in IE7
        if (Core.getBrowser() == "ie7") {
            inputSelect.width(inputText.outerWidth());
        }
    }

    function _normalize(text) {
        text = text.toLowerCase();
        text = text.replace('Г©', 'e');
        text = text.replace('Гј', 'u');
        text = text.replace(' - ', 'В вЂ“ ');
        text = text.replace('В вЂ“ ', ' ');
        return text;
    }

    this.initialize();
}

/**
 * Reformats a date per the user's time zone and locale.
 *
 * @copyright   2010, Blizzard Entertainment, Inc.
 * @class       DateTime
 * @requires	Core
 * @example
 *
 *      var times = new DateTime('#content'); // will apply to all <time/> elements within <div id="content"/>
 */
var DateTime = Class.extend({

    /**
     * jQuery objects for specific elements.
     */
    times: null,

    /**
     * Localization settings.
     */
    format: '',
    locale: Core.locale,

    /**
     * Initialize the class and apply the config.
     */
    init: function(full) {

        this.times = $('time');

        switch (this.locale) {
            default:
            case 'cs-cz':
            case 'de-de':
            case 'pl-pl':
                this.format = 'dd.MM.yyyy';
                if (full) {
                    this.format = this.format + ' HH:mm';
                }
                break;
            case 'en-us':
                this.format = 'MM/dd/yyyy';
                if (full) {
                    this.format = this.format + ' hh:mm a';
                }
                break;
            case 'en-gb':
            case 'es-es':
            case 'es-mx':
            case 'fr-fr':
            case 'pt-br':
            case 'it-it':
            case 'ru-ru':
                this.format = 'dd/MM/yyyy';
                if (full) {
                    this.format = this.format + ' HH:mm';
                }
                break;
            case 'en-sg':
                this.format = 'dd/MM/yyyy';
                if (full) {
                    this.format = this.format + ' hh:mm a';
                }
                break;
            case 'ja-ja':
                this.format = 'yyyy/MM/dd';
                if (full) {
                    this.format = this.format + ' HH:mm';
                }
                break;
            case 'ko-kr':
                this.format = 'yyyy.MM.dd';
                if (full) {
                    this.format = this.format + ' HHм‹њ mmл¶„';
                }
                break;
            case 'zh-cn':
                this.format = 'yyyyе№ґMMжњ€ddж—Ґ';
                if (full) {
                    this.format = this.format + ' HH:mm';
                }
                break;
            case 'zh-tw':
                this.format = 'yyyy-MM-dd';
                if (full) {
                    this.format = this.format + ' HH:mm';
                }
                break;
        }

        if (this.times.length) {
            this.localize();
        }

    },

    localize: function() {

        var times = this.times,
            format = this.format,
            locale = this.locale,
            datetime = null,
            individualFormat = this.format;

        for (var i = 0, len = times.length; i < len; i++) {
            time = $(times[i]);
            if (time.data("format")) {
                individualFormat = time.data("format");
            } else {
                individualFormat = format;
            }
            datetime = time.attr('datetime');
            datetime = Core.formatDatetime(individualFormat, datetime);
            if (!datetime) {
                return;
            }
            datetime = datetime.replace('/0', '/');
            if (datetime.substr(0, 1) === '0') {
                datetime = datetime.substr(1);
            }
            if (locale === 'en-us' || locale === 'en-sg') {
                datetime = datetime.replace(' 0', ' ');
            }

            if ($.browser.msie) {
                time.parent().html(datetime);
            } else {
                time.html(datetime);
            }
        }

    }

});


var times = new DateTime(fullTimeDisplay);

/**
 * Functionality for user Account balance.
 *
 * @copyright   2011, Blizzard Entertainment, Inc.
 * @class       accountBalance
 * @example
 *
 */

var accountBalance = {

    accountBalanceCurrency : null,
    accountBalanceCenter: $("#accountBalanceCenter"),
    accountBalanceMenu: $("#accountBalance-menu"),
    changeCurrencyMenu: $("#changeCurrency-menu"),
    accountBalancePrimaryBalance: $("#primary-balance"),
    refreshBalanceElement: $("#refreshBalance"),
    refreshingBalanceElement: $("#refreshingBalance"),
    changeCurrencyThrottled: $("#changeCurrencyThrottled"),
    /**
     * Format Sample -
     *     'USD': {
     *        'format': '$ {0} USD',
     *        'groupSize': 3,
     *        'delimiter': ',',
     *        'point': '.'
     *     }
     */
    currencyMap: {},

    initialize: function() {
        if (accountBalance.accountBalanceCurrency){
            accountBalance.setPrimary(accountBalance.accountBalanceCurrency);
        }

        accountBalance.bindCurrencies();
    },

    bindCurrencies: function() {
        this.accountBalanceMenu.find(".switch-currency").each(function(){
            $(this).bind({
                'click': function() {
                    var flagSelected = $(this).hasClass("selected");
                    if(!flagSelected){
                        accountBalance.accountBalanceCurrency = $(this).attr("id");
                        accountBalance.setAccountBalanceCurrency();
                    }
                }
            });
        });

        this.changeCurrencyMenu.find(".switch-currency").each(function(){
            $(this).bind({
                'click': function() {
                    accountBalance.accountBalanceCurrency = $(this).attr("id");
                    accountBalance.setAccountBalanceCurrency();
                }
            });
        });
    },

    selectCurrency: function(currency) {
        var flagSelected = $("#" + currency).hasClass("selected");
        if(!flagSelected){
            accountBalance.accountBalanceCurrency = currency;
            accountBalance.setAccountBalanceCurrency();
        }
    },

    refreshBalance: function() {
        if(Core.browser=="ie6" || Core.browser=="ie7" || Core.browser=="ie8" || Core.browser=="ie9"){
            window.location.reload();
        }

        this.refreshBalanceElement.hide();
        this.refreshingBalanceElement.show();

        var postData = { };

        $.ajax({
            type: 'POST',
            timeout: 60000,
            data: postData,
            dataType: 'json',
            url: '/data/refresh-balance',
            success: function(msg) {
                if (msg !== '') {
                    var currencies = msg;
                    var selectedCurrency = accountBalance.accountBalanceCurrency;
                    var newElements = "";

                    $("#accountBalance-menu ul li.switch-currency").remove();

                    for (var c in currencies) {
                        if (currencies[c]['balance'] > 0) {
                            var v = accountBalance.getBalanceFormatting(currencies[c]['balance'], currencies[c]['currency']);
                            var className = "switch-currency";
                            if (selectedCurrency == currencies[c]['currency']) {
                                className += " selected";
                                $("#primary-balance").text(v);
                            }
                            var newEvent = " onclick='accountBalance.selectCurrency(&quot;" + currencies[c]['currency'] + "&quot;);' ";
                            newElements += "<li id='" + currencies[c]['currency'] + "' class='" + className + "'" + newEvent + "><span>" + v + "</span></li>";
                        }
                    }
                    $("#accountBalance-menu ul").children(":first").before(newElements);
                }
            },
            error: function() {
                //accountBalance.setError();
            }
        });

        accountBalance.bindCurrencies();

        setTimeout('accountBalance.toggleRefresh()', 1000);

    },

    toggleRefresh: function() {
        this.refreshBalanceElement.show();
        this.refreshingBalanceElement.hide();
    },

    setPrimary: function(value) {
        if (accountBalance.changeCurrencyMenuFlag) {
            window.location.reload();
            return false;
        }

        accountBalance.accountBalanceCurrency = value;

        this.accountBalanceMenu.find(".switch-currency").removeClass("selected");

        var primaryEl = this.accountBalanceCenter.find("#"+value).addClass("selected");

        var primaryCurrency = primaryEl.find("span").html();
        if (primaryCurrency != null) {
            this.accountBalancePrimaryBalance.html(primaryCurrency);
        }else{
            price = 0.00;
            this.accountBalancePrimaryBalance.html(accountBalance.getBalanceFormatting(price));
        }
    },

    setAccountBalanceCurrency: function(selected) {
        if (selected) {
            accountBalance.accountBalanceCurrency = selected;
        }
        var postData = { prefix: 'CUR', value: accountBalance.accountBalanceCurrency };
        $.ajax({
            type: 'POST',
            timeout: 60000,
            url: '/data/set-account-cookie',
            data: postData,
            dataType: 'json',
            success: function(msg) {
                accountBalance.changeCurrencyThrottled.hide();

                if (msg !== '' && msg.successful !== '') {
                    if (msg.successful === true && msg.value !== undefined) {
                        accountBalance.setPrimary(msg.value);

                        if (selected) {
                            location.href = "transaction-history?currency=" + msg.value;
                        }
                    } else if (msg.successful === false && msg.value !== undefined && msg.value === 'THROTTLED') {
                        accountBalance.changeCurrencyMenu.hide();
                        accountBalance.changeCurrencyThrottled.show();
                    }
                }
            },
            error: function() {
                //accountBalance.setError();
            }
        });
    },

    getBalanceFormatting: function(number, currency) {
        currency = currency || accountBalance.accountBalanceCurrency;

        number = accountBalance._getNumber(number, currency);
        number = accountBalance._getNumberGroup(number, currency);
        number = accountBalance._getCurrencyFormat(number, currency);

        return number;
    },

    _getNumber: function (number, currency) {
        if (number){
            if (typeof number !== 'number') {
                number = number.replace(",", ".").replace(/[^0-9\.]/g, '');
            }

            number = parseFloat(parseFloat(number, 10).toFixed(2), 10);
        }

        return number;
    },

    _getNumberGroup: function (number, currency) {

        currency = currency || accountBalance.accountBalanceCurrency;

        if((currency == 'CPT' || currency == "KRW") && number == 0){
            return number;
        }

        var i,
            j,
            groupSize,
            delimiter,
            point,
            reg = /(^[+-]?\d+)(\d{3})/,
            returnNumber;

        if (accountBalance.currencyMap[currency]) {
            groupSize = accountBalance.currencyMap[currency].groupSize || 3,
                delimiter = accountBalance.currencyMap[currency].delimiter || ",",
                point = accountBalance.currencyMap[currency].point || ".";
        }else{
            groupSize = 3,
                delimiter = ",",
                point = ".";
        }

        number += "";
        number = number.split(".");

        while (reg.test(number[0])){
            number[0] = number[0].replace(reg, '$1' + delimiter + '$2');
        }

        if (!number[1]) {
            number[1] = "00";
        }else{
            if (number[1].length >= 2) {
                number[1] = number[1].substring(0,2);
            }else{
                number[1] += "0";
            }
        }

        if ( (currency == 'CPT' || currency == "KRW") && number[1] == "00") {
            returnNumber = number[0];
        } else {
            returnNumber = number[0] + point + number[1]
        }

        return returnNumber;
    },

    _getCurrencyFormat: function (number, currency, useAlt) {
        currency = currency || accountBalance.accountBalanceCurrency;

        if (accountBalance.currencyMap[currency]) {
            if(useAlt && typeof(accountBalance.currencyMap[currency].altFormat) == "string") {
                number = accountBalance.currencyMap[currency].altFormat.replace("{0}", number);
            } else {
                number = accountBalance.currencyMap[currency].format.replace("{0}", number);
            }
        }

        return number;
    },

    setError: function() {
        return false;
    }
}

/**
 * Functionality for user Account region.
 *
 * @copyright   2012, Blizzard Entertainment, Inc.
 * @class       Region
 * @example
 * Region.getRegion("us") retrun 1
 * Region.getRegion(1) retrun "us"
 */
var Region = {
    data: {
        'us' : 1, 'eu' : 2, 'kr' : 3, 'tw' : 4, 'cn' : 5, 'sea' : 6
    },

    getRegion: function(id) {
        return Region.getByString(id) || Region.getByNumber(id);
    },

    getByString: function(id){
        if (typeof (id) === 'string') {
            return Region.data[id.toLowerCase()];
        }
        return false;
    },

    getByNumber: function(id){
        for( var key in Region.data ) {
            if (Region.data.hasOwnProperty(key) && Region.data[key] === id) {
                return key;
            }
        }
        return false;
    }
}

/**
 * Functionality for input validator.
 *
 * @copyright   2012, Blizzard Entertainment, Inc.
 * @class       InputValidator
 * @example
 *  var objInput = {
 *      resultButton : "redeem-code",
 *      input : [{
 *         id : "cardNo"
 *      },{
 *         id : "cardPin"
 *      }]
 * }
 * InputValidator.initialize(objInput);
 */

var InputValidator = {
    initialize:function(objInput){
        var i,
            inputLength = objInput.input.length,
            result;
        for (i = 0; i < inputLength; i++) {
            $("#" + objInput.input[i].id).bind('keyup change', function(){
                result = InputValidator.validateMaxLength();
                InputValidator.setResult(result);
            })

        }

    },

    validateMaxLength:function(){
        var i,
            inputLength = objInput.input.length,
            maxLength,
            length,
            result;
        for (i = 0; i < inputLength; i++) {
            maxLength = $("#" + objInput.input[i].id).attr("maxlength");
            length = $("#" + objInput.input[i].id).val().length;

            if (maxLength == length){
                result = true;
            }else{
                return false;
            }
        }
        return result;
    },


    setResult:function(result){
        if(result){
            UI.enableButton("#" + objInput.resultButton);
        }else{
            UI.disableButton("#" + objInput.resultButton);
        }
    }
}

/*
 * Display error messages
 *
 * @param alertTitle	Header for alert container
 * @param errorMessages	Array of errors to display
 */
function showErrors(alertTitle, errorMessages) {
    var errorCount = errorMessages.length,
        container = $('.templates > .error').clone(),
        header = container.find('.error-title'),
        error = container.find('.error-message'),
        errors = container.find('.error-messages');

    if (container.length) {
        header.html(alertTitle);
        if (errorCount > 1) {
            error.remove();
            var item;
            for (var i = 0; i < errorCount; i++) {
                item = document.createElement('li');
                item.appendChild(document.createTextNode(errorMessages[i]));
                errors.append(item);
            }
        } else {
            errors.remove();
            error.append(errorMessages[0]);
        }

        $('#content > .alert').remove();
        $('#content').prepend(container);
        window.location.href = window.location.pathname + window.location.search + '#form-errors';
    }
}

/*
 * for under ie9 indexOf method
 */
if (!Array.prototype.indexOf)
{
    Array.prototype.indexOf = function(elt /*, from*/)
    {
        var len = this.length >>> 0;

        var from = Number(arguments[1]) || 0;
        from = (from < 0)
            ? Math.ceil(from)
            : Math.floor(from);
        if (from < 0)
            from += len;

        for (; from < len; from++)
        {
            if (from in this &&
                this[from] === elt)
                return from;
        }
        return -1;
    };
}