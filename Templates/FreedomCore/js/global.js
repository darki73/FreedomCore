/* В©2013 Blizzard Entertainment, Inc. All rights reserved. */
"use strict";
$(function() {
    $.fn.tooltipster("setDefaults", {
        onlyOne: true,
        positionTracker: (typeof window.ScriptPackages !==
        "undefined"),
        speed: 100
    });
    $("[data-tooltips]").not("[data-tooltips=tap]").tooltipster();
    $("[data-tooltips=tap]").tooltipster({
        delay: 0,
        position: "right",
        timer: 5000,
        trigger: "click"
    });
    $(document).ajaxStart(function() {
        $("#ajax-indicator").addClass(
            "fade-in spinner-40-battlenet");
    }).ajaxStop(function() {
        $("#ajax-indicator").removeClass(
            "fade-in spinner-40-battlenet");
    });
    $("#change-language, #service .service-language a").off("click").on(
        "click", function() {
            return Locale.openMenu("#change-language", "?");
        });
    $("body").off("click.external").on("click.external",
        "a[data-external]", function(b) {
            var a = $(this);
            App.Package.execute(function(f) {
                if (a.attr("data-external") === "sso") {
                    a.attr("rel", "external-sso");
                } else {
                    a.attr("rel", "external");
                }
                var d = a.attr("data-continue");
                if (d) {
                    var e = a.attr("href");
                    var c = a.attr("rel");
                    a.attr("href", d).removeAttr("rel");
                    f.handleClickEvent(e, c);
                }
            }, function() {
                a.attr("target", a.attr("target") ||
                "_blank");
            });
            a.removeAttr("data-external");
        }).on("click.disabledAnchors", function(a) {
            if (a.target.tagName.toLowerCase() === "a" && a.target.hasAttribute(
                    "disabled")) {
                a.preventDefault();
                a.stopPropagation();
            }
        });
});
var App = App || {};
App.Package = {
    execute: function() {
        if (arguments.length > 0 && $.isFunction(arguments[0])) {
            this._execute2.apply(this, arguments);
        } else {
            if (arguments.length > 1) {
                this._execute3.apply(this, arguments);
            }
        }
    },
    executeAsync: function() {
        var a = this,
            b = arguments;
        window.setTimeout(function() {
            a.execute.apply(a, b);
        }, 0);
    },
    _execute2: function(b, a) {
        if (typeof window.ScriptPackages !== "undefined") {
            b(window.ScriptPackages);
        } else {
            if ($.isFunction(a)) {
                a();
            }
        }
    },
    _execute3: function(a, c, b) {
        if (a && $.isFunction(c)) {
            if (typeof window.ScriptPackages !== "undefined" && typeof window
                    .ScriptPackages[a] !== "undefined") {
                c(window.ScriptPackages[a]);
            } else {
                if ($.isFunction(b)) {
                    b();
                }
            }
        }
    }
};
App.Background = {
    _errorHandler: function() {
        App.Package.execute("ErrorHandler", function(a) {
            a.handleError();
        });
    },
    streamToApp: function(g, f) {
        var b = this;
        try {
            var c = $(".navbar-fixed").outerHeight() || 0;
            App.Package.executeAsync("ShopScreen", function(e) {
                e.setBackgroundDataBase64("", 200, c, g);
            });
            var a = new Image();
            if (f) {
                a.crossOrigin = "use-credentials";
            } else {
                a.crossOrigin = "anonymous";
            }
            $(a).on("load", function() {
                var e = document.createElement("canvas");
                e.width = a.naturalWidth;
                e.height = a.naturalHeight;
                var h = e.getContext("2d");
                h.drawImage(a, 0, 0);
                var j = e.toDataURL("image/png");
                var i = j.split(";base64,");
                if (i && (i.length > 1)) {
                    App.Package.execute("ShopScreen", function(
                        k) {
                        k.setBackgroundDataBase64(i[1],
                            200, c, g);
                    });
                }
            }).on("abort", b._errorHandler).on("error", b._errorHandler);
            a.src = g;
        } catch (d) {
            b._errorHandler();
        }
    }
};
App.Navigation = {
    confirmExit: function(a) {
        App.Package.execute("ApplicationBehavior", function(b) {
            b.setWarningOnNavigation(true, a);
        });
    },
    goToPlayScreen: function(a) {
        App.Package.execute("ApplicationBehavior", function(b) {
            b.focusGameTab(a);
        });
    }
};
$(function() {
    App.Package.execute("LoadingIcon", function(a) {
        a.enableLoadingIcon(false);
    });
    $("body").on("click.play", "a[data-play-screen]", function(b) {
        var a = $(this).attr("data-play-screen");
        App.Navigation.goToPlayScreen(a || "");
        b.preventDefault();
    });
});
(function(g, d) {
    var k = "data-gtm-click",
        a = "data-gtm-position",
        c = "data-gtm-promo-name",
        h = "data-gtm-list",
        e = "data-gtm-product-id",
        b = "data-gtm-product-name";

    function j(l, m) {
        return {
            add: {
                products: [{
                    id: l,
                    name: m,
                    quantity: 1
                }]
            }
        };
    }

    function i(l) {
        if (typeof g.ScriptPackages !== "undefined") {
            return false;
        }
        if (l && l.href) {
            g.document.location = l.href;
        }
    }
    var f = {
        init: function() {
            d("body").on("click.shopgtm", "[" + k + "]", function(l) {
                var m = d(this).attr(k);
                if (m && d.isFunction(f[m]) && typeof g.dataLayer !==
                    "undefined") {
                    f[m].call(f, this, l);
                    l.preventDefault();
                }
            });
        },
        ecommerceEvent: function(n, l, o) {
            var m = d.Deferred();
            d.when(m).always(function() {
                i(o);
            });
            try {
                var p = g.setTimeout(function() {
                    m.reject("Analytics timed out");
                }, 2000);
                if (n && l) {
                    g.dataLayer.push({
                        ecommerce: d.extend({
                            currencyCode: (g.Msg &&
                            g.Msg.userCurrency
                            ) || "USD"
                        }, l),
                        eventCallback: function() {
                            g.clearTimeout(p);
                            m.resolve();
                        },
                        event: n
                    });
                }
            } catch (q) {
                m.reject(q);
                return;
            }
            return m.promise();
        },
        productCardClick: function(n, u) {
            var o = d(n).attr(h),
                p = d(n).attr(a),
                q = d(n).attr(e),
                v = d(n).attr(b),
                l = d(n).attr(c);
            var r = [];
            if (q && v) {
                var t = q.split("|");
                var s = v.split("|");
                for (var m = 0; m < t.length; m++) {
                    r.push({
                        id: t[m],
                        name: s[m] || "",
                        position: p
                    });
                }
            }
            this.ecommerceEvent("productCardClick", {
                click: {
                    actionField: {
                        list: o
                    },
                    products: r
                }
            }, n);
            if (l) {
                this.ecommerceEvent("productCardClick", {
                    promoClick: {
                        promotions: [{
                            name: l,
                            position: p,
                            products: r,
                            actionField: {
                                list: o
                            }
                        }]
                    }
                }, n);
            }
        },
        homepageCarouselClick: function(n, l) {
            var o = d(n).attr(c),
                m = d(n).attr(a);
            this.ecommerceEvent("homepageCarouselClick", {
                promoClick: {
                    promotions: [{
                        name: o,
                        position: m
                    }]
                }
            }, n);
        },
        shopBuyNowButton: function(m, l) {
            var n = d(m).attr(e),
                o = d(m).attr(b);
            this.ecommerceEvent("shopBuyNowButton", j(n, o), m);
        },
        shopExternalProductLink: function(m, l) {
            var n = d(m).attr(e),
                o = d(m).attr(b);
            this.ecommerceEvent("shopExternalProductLink", j(n, o),
                m);
        },
        shopGiftButton: function(m, l) {
            var n = d(m).attr(e),
                o = d(m).attr(b);
            this.ecommerceEvent("shopGiftButton", j(n, o), m);
        },
        shopTryButton: function(m, l) {
            var n = d(m).attr(e),
                o = d(m).attr(b);
            this.ecommerceEvent("shopTryButton", j(n, o), m);
        },
        upgradeClick: function(m, l) {
            var p = d(m).attr(a),
                n = d(m).attr(e),
                o = d(m).attr(b),
                r = d(m).parent("form");
            try {
                this.ecommerceEvent("upgradeClick", {
                    click: {
                        actionField: {
                            list: "Bundle Select"
                        },
                        products: [{
                            id: n,
                            name: o,
                            position: p
                        }]
                    }
                }, m);
                this.ecommerceEvent("upgradeClick", j(n, o), m);
            } catch (q) {} finally {
                r.submit();
            }
        }
    };
    g.shopgtm = g.shopgtm || f;
    d(function() {
        g.shopgtm.init();
    });
})(window, jQuery);