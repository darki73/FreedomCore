/**
 * Object.create() polyfill for older browsers.
 */
if (!Object.create) {
    Object.create = function(o) {
        if (arguments.length > 1) {
            throw new Error('Object.create implementation only accepts the first parameter.');
        }

        function F() {}
        F.prototype = o;
        return new F();
    };
}


/**
 * Object.getPrototypeOf polyfill for older browsers.
 *
 * @see http://ejohn.org/blog/objectgetprototypeof/
 */
if (!Object.getPrototypeOf) {
    if (typeof 'test'.__proto__ === 'object') {
        Object.getPrototypeOf = function(object) {
            return object.__proto__;
        };
    } else {
        Object.getPrototypeOf = function(object) {
            // May break if the constructor has been tampered with
            return object.constructor.prototype;
        };
    }
}


/**
 * Polyfill for String.fromCodePoint() in ES6.
 * Generates strings from Unicode code points. String.fromCharCode() cannot handle high code points and should be deprecated.
 */
if (!String.fromCodePoint) {
    /*!
     * ES6 Unicode Shims 0.1
     * (c) 2012 Steven Levithan <http://slevithan.com/>
     * MIT License
     */
    String.fromCodePoint = function fromCodePoint () {
        var chars = [], point, offset, units, i;
        for (i = 0; i < arguments.length; ++i) {
            point = arguments[i];
            offset = point - 0x10000;
            units = point > 0xFFFF ? [0xD800 + (offset >> 10), 0xDC00 + (offset & 0x3FF)] : [point];
            chars.push(String.fromCharCode.apply(null, units));
        }
        return chars.join("");
    };
}


/**
 * Prototype extensions.
 */
if (!String.prototype.trim) {
    String.prototype.trim = function() {
        return $.trim(this);
    };
}

if (!String.prototype.capitalize) {
    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    };
}


/**
 * The following 2 functions will heck if jQuery.expr.createPseudo exist.
 * jQuery.expr.createPseudo was introduced in jQuery 1.8, this check ensures apps using older version
 * of jQuery won't break.
 */

/**
 * caseInsensitiveContains jquery custom pseudoselector
 *
 */
jQuery.expr[":"].caseInsensitiveContains = typeof(jQuery.expr.createPseudo) == "function" ?
    jQuery.expr.createPseudo(function(arg) {
        return function(elem) {
            return jQuery(elem).text().toLocaleLowerCase().indexOf(arg.toLocaleLowerCase()) >= 0;
        };
    }) :
    function(elem, i, match) {
        return jQuery(elem).text().toLocaleLowerCase().indexOf(match[3].toLocaleLowerCase()) >= 0;
    };

/**
 * caseInsensitiveStartsWith jquery custom pseudoselector
 *
 */
jQuery.expr[":"].caseInsensitiveStartsWith =  typeof(jQuery.expr.createPseudo) == "function" ?
    jQuery.expr.createPseudo(function(searchString) {
        return function(elem) {
            return jQuery(elem).text().toLocaleLowerCase().indexOf(searchString.toLocaleLowerCase()) === 0;
        };
    }) :
    function(elem, i, match) {
        return jQuery(elem).text().toLocaleLowerCase().indexOf(match[3].toLocaleLowerCase()) === 0;
    };


/**
 * jQuery extensions.
 */
if (!jQuery.Event.prototype.stop) {
    jQuery.Event.prototype.stop = function() {
        this.preventDefault();
        this.stopPropagation();
    };
}

/**
 * Setup ajax calls.
 */
$.ajaxSetup({
    error: function(xhr) {
        if (xhr.readyState !== 4) {
            return false;
        }

        if (xhr.getResponseHeader("X-App") === "login") {
            Login.openOrRedirect();
            return false;
        }

        if (xhr.status) {
            switch (xhr.status) {
                case 301:
                case 302:
                case 307:
                case 403:
                case 404:
                case 500:
                case 503:
                    return false;
                    break;
            }
        }

        return true;
    },
    statusCode: {
        403: function() {
            // Via AJAX calls
            Login.openOrRedirect();
        }
    }
});