/* В©2013 Blizzard Entertainment, Inc. All rights reserved. */
"use strict";
var ProductAgeGate = {
    form: null,
    actions: null,
    videos: null,
    target: "",
    origin: "",
    trackUrl: "",
    pagePrefix: "",
    create: function(a) {
        if (typeof a === "object") {
            this.trackUrl = a.trackUrl || "";
            this.pagePrefix = a.pagePrefix || "";
        }
        this.form = $("#agegate-form");
        this.actions = $(".buybox");
        this.videos = $('.video-enabled [data-video-inline="agegate"]');
        if (this.form.length && (this.actions.length || this.videos.length)) {
            this.target = document.location.toString();
            this.registerEvents();
        }
        return this;
    },
    registerEvents: function() {
        this.actions.on("click.agegate",
            '[data-target="#agegate-form"]', $.proxy(this._onClickProductAction,
                this));
        this.videos.on("click.agegate", $.proxy(this._onClickVideoAction,
            this));
        this.form.on("show.agegate", $.proxy(this._onShowAgeGate, this));
        this.form.on("done.agegate", $.proxy(this._onDoneAgeGate, this));
    },
    _onClickProductAction: function(b) {
        if (b.target.tagName.toLowerCase() === "a") {
            var a = $(b.target);
            this.origin = "link";
            if (a.attr("data-src")) {
                this.target = a.attr("data-src");
            } else {
                this.target = a.attr("href") || document.location.toString();
                a.attr("href", "#");
                a.attr("data-src", this.target);
            }
            b.preventDefault();
        }
    },
    _onClickVideoAction: function(a) {
        this.origin = "video";
        this.target = a.delegateTarget;
        a.preventDefault();
    },
    _onShowAgeGate: function(a) {
        if (typeof ga !== "undefined" && this.trackUrl) {
            ga("bnetgtm.send", "pageview", this.pagePrefix + this.trackUrl);
        }
    },
    _onDoneAgeGate: function(b, a) {
        if (a === "PASSED") {
            this.form.modal("hide");
            if (this.target && this.origin === "link") {
                document.location = encodeURI(this.target);
            } else {
                if (this.target && this.origin === "video" && typeof MediaLightbox !==
                    "undefined") {
                    MediaLightbox.showInlineProductVideo(this.target);
                }
            }
            this._removeAgeGateBehaviors();
        }
    },
    _removeAgeGateBehaviors: function() {
        this.actions.off("click.agegate").find("[data-toggle]").removeAttr(
            "data-toggle");
        this.videos.off("click.agegate").removeAttr("data-toggle");
        this.actions.find("[data-src]").each(function() {
            var a = $(this);
            a.attr("href", a.attr("data-src")).removeAttr(
                "data-src");
        });
        this.videos.on("click.agegate.passed", function(a) {
            MediaLightbox.showInlineProductVideo(a.delegateTarget);
        });
    }
};
var showModalHeader = function(a) {
    var c = $(a).data("nid-block-action"),
        b;
    if (c) {
        b = $("#nid-state");
        b.find("h1").addClass("hide");
        b.find("#" + c).removeClass("hide");
    }
};
$(function() {
    var a = $(".product-selection");
    a.on("click.productSelection", ".radio-label", function() {
        $("#product-actions-target").empty().append($(this).next(
            ".product-actions").clone());
    });
    a.find('input[type="radio"]:checked').trigger(
        "click.productSelection");
    if (window.disablePurchaseAndMedia) {
        $(".product-actions .btn.disabled, .single-media").on("click",
            function(b) {
                showModalHeader(b.currentTarget);
            });
    }
});
$(function() {
    var a = $('.video-enabled [data-video-inline="inline"]');
    if (a.length) {
        if (/(iPad|iPhone|iPod)/g.test(navigator.userAgent)) {
            MediaLightbox.showInlineProductVideo(a);
        } else {
            a.click(function(b) {
                MediaLightbox.showInlineProductVideo(b.delegateTarget);
            });
        }
    }
});
var MediaLightbox = function(b, a) {
    this.element$ = $(b);
    this.options = a || {};
    this.series = {};
    this.ageGatePassed = false;
    this.ageGateContinue = null;
    this.currentPlayer = null;
    this.videoWaiting = false;
    this.init();
};
MediaLightbox.showInlineProductVideo = function(b) {
    var e = $(b);
    var d = e.attr("data-video-id"),
        c = e.attr("data-video-target");
    e.attr("data-video-played", "false");
    if (d && c) {
        var a = $("#" + c);
        if (a.length) {
            a.append('<div id="' + c + '_1"></div>');
            return MediaLightbox._embedYouTubeVideo(d, c + "_1", function() {
                e.hide();
                a.show();
            }, function(f) {
                if (typeof ga !== "undefined" && f.data === YT.PlayerState
                        .PLAYING && e.attr("data-video-played") !==
                    "true") {
                    e.attr("data-video-played", "true");
                    ga("bnetgtm.send", "event",
                        "Shop-product video play", "Click-play",
                        Msg.productSlug || window.location.pathname
                    );
                }
            });
        }
    }
    return undefined;
};
MediaLightbox.prototype.init = function() {
    var c = this.element$;
    var b = this.options;
    var a = this;
    c.find(b.targetImage).on("load.mediaLightbox", function(d) {
        c.find(b.spinner).hide();
        $(this).show();
    });
    c.on("hide", function() {
        if (a.currentPlayer) {
            a.currentPlayer.stopVideo();
        }
    });
    a.previousLink$ = c.find(b.previousLink);
    a.nextLink$ = c.find(b.nextLink);
    if (Boolean(b.ageGateEnabled) && b.ageGateModal) {
        $(b.ageGateModal).on("done.agegate", $.proxy(this._ageGate, this));
    }
    $(b.anchors).add(a.previousLink$).add(a.nextLink$).on(
        "click.mediaLightbox", function(h) {
            h.stopPropagation();
            h.preventDefault();
            var f = $(h.delegateTarget);
            var g = f.attr(b.seriesAttrib),
                d = parseInt(f.attr(b.indexAttrib), 10);
            a.showImage(g, d);
        });
    $(window).on("keyup.mediaLightbox", function(d) {
        if (c.css("display") === "none") {
            return;
        }
        switch (d.which) {
            case 27:
                c.modal("hide");
                break;
            case 37:
                a.previousLink$.trigger("click");
                break;
            case 39:
                a.nextLink$.trigger("click");
                break;
        }
    });
};
MediaLightbox.prototype.getMedia = function(b, a) {
    var c = this.series[b];
    if ($.isArray(c) && (a > -1) && (a < c.length)) {
        return c[a];
    }
    return undefined;
};
MediaLightbox.prototype.registerSeries = function(a, b) {
    if (a && $.isArray(b)) {
        this.series[a] = b;
    }
};
MediaLightbox.prototype.showImage = function(b, a) {
    var d = this.getMedia(b, a),
        c = this.options;
    if (d) {
        if (d.type === "VIDEO") {
            if (!!c.ageGateEnabled && c.ageGateModal && this.ageGatePassed ===
                false) {
                this.ageGateContinue.seriesName = b;
                this.ageGateContinue.imageIndex = a;
                this.element$.modal("hide");
                $(c.ageGateModal).modal("show");
                return;
            }
            this._showVideo(d);
        } else {
            this._showImage(d);
        }
        this._showLabel(d);
        this._setupPreviousAndNext(b, a);
        this.element$.modal("show");
    }
};
MediaLightbox.prototype._ageGate = function(b, a) {
    if (a === "PASSED" && this.ageGateContinue) {
        this.ageGatePassed = true;
        $(this.options.ageGateModal).modal("hide");
        this.showImage(this.ageGateContinue.seriesName, this.ageGateContinue
            .imageIndex);
    }
};
MediaLightbox._embedYouTubeVideo = function(d, c, a, b) {
    return new YT.Player(c, {
        height: "100%",
        width: "100%",
        videoId: d,
        playerVars: {
            autoplay: 1,
            modestbranding: 1,
            rel: 0,
            showinfo: 0
        },
        events: {
            onReady: function(e) {
                if ($.isFunction(a)) {
                    a(e);
                }
            },
            onStateChange: function(e) {
                if ($.isFunction(b)) {
                    b(e);
                }
            }
        }
    });
};
MediaLightbox.prototype._hideImage = function() {
    this.element$.find(this.options.targetImage).hide();
};
MediaLightbox.prototype._hideVideo = function() {
    if (this.currentPlayer) {
        this.currentPlayer.stopVideo();
    }
    $("#" + this.options.targetVideoFrameID).hide();
};
MediaLightbox.prototype._showLabel = function(b) {
    var a = this.element$.find(this.options.targetLabel);
    if (b && b.label) {
        a.text(b.label).show();
    } else {
        a.hide();
    }
};
MediaLightbox.prototype._setupPreviousAndNext = function(b, a) {
    var c = this.series[b];
    if (c && (a > -1) && (a < c.length)) {
        if ((c.length > 1) && a > 0) {
            this.previousLink$.attr(this.options.seriesAttrib, b).attr(this
                .options.indexAttrib, a - 1).show();
        } else {
            this.previousLink$.hide();
        } if ((c.length > 1) && a < (c.length - 1)) {
            this.nextLink$.attr(this.options.seriesAttrib, b).attr(this.options
                .indexAttrib, a + 1).show();
        } else {
            this.nextLink$.hide();
        }
    }
};
MediaLightbox.prototype._showImage = function(c) {
    if (c && c.url) {
        var b = this.element$.find(this.options.targetImage),
            a = this.element$.find(this.options.spinner);
        this._hideVideo();
        if (b.attr("src") !== c.url) {
            a.show();
            b.hide().attr("src", c.url);
        } else {
            a.hide();
            b.show();
        }
    }
};
MediaLightbox.prototype._showVideo = function(d) {
    if (d && d.url) {
        var c = $("#" + this.options.targetVideoFrameID),
            b = this.element$.find(this.options.spinner),
            a = this;
        a._hideImage();
        if (c.attr("data-src") !== d.url) {
            b.show();
            a._hideVideo();
            c.attr("data-src", d.url);
            if (a.currentPlayer) {
                a.videoWaiting = true;
                a.currentPlayer.loadVideoById(d.url);
            } else {
                c.empty().append('<div id="' + a.options.targetVideoFrameID +
                '_1"></div>');
                a.videoWaiting = true;
                a.currentPlayer = MediaLightbox._embedYouTubeVideo(d.url, a
                    .options.targetVideoFrameID + "_1", null, function(
                    f) {
                    if (f.data === YT.PlayerState.PLAYING && a.videoWaiting ===
                        true) {
                        a.videoWaiting = false;
                        b.hide();
                        c.show();
                        if (typeof ga !== "undefined") {
                            ga("bnetgtm.send", "event",
                                "Shop-product video play",
                                "Click-play", Msg.productSlug ||
                                window.location.pathname);
                        }
                    }
                });
            }
        } else {
            b.hide();
            c.show();
            if (a.currentPlayer) {
                a.currentPlayer.seekTo(0);
                a.currentPlayer.playVideo();
            }
        }
    }
};
$.fn.mediaLightbox = function(a) {
    var b = $(this).data("mediaLightbox");
    if (Boolean(b) === false) {
        b = new MediaLightbox(this, a || $.fn.mediaLightboxDefaultOptions);
        $(this).data("mediaLightbox", b);
    }
    return this;
};
$.fn.mediaLightboxDefaultOptions = {
    targetImage: ".feature-lightbox-target-image",
    targetVideoFrameID: "featureLightboxTargetVideo",
    targetLabel: ".feature-lightbox-label",
    spinner: ".feature-lightbox-spinner",
    anchors: "[data-media-lightbox-series]",
    seriesAttrib: "data-media-lightbox-series",
    indexAttrib: "data-media-lightbox-index",
    previousLink: ".feature-lightbox-prev",
    nextLink: ".feature-lightbox-next",
    ageGateModal: "",
    ageGateEnabled: false
};
$(function() {
    function a(c, b) {
        $(b).animate({
            scrollTop: $(c).offset().top
        }, {
            complete: function() {
                window.location.hash = c;
            }
        });
    }
    $('.product-template:not(.app) .product-container a[href^="#"]').on(
        "click.scrollAnimate", function(c) {
            var b = $(this).attr("href");
            if (b && b.length > 1) {
                c.stopPropagation();
                c.preventDefault();
                a(b, "html, body");
            }
        });
    $('.product-template.app .product-container a[href^="#"]').add(
        "#topbutton a").on("click.scrollAnimate", function(c) {
            var b = $(this).attr("href");
            if (b && b.length > 1) {
                c.stopPropagation();
                c.preventDefault();
                a(b, ".body-content");
            }
        });
    $(".product-template.app .body-content").scroll(function() {
        if ($(".body-content").scrollTop() > 500) {
            $("#topbutton").removeClass("shelved");
        } else {
            $("#topbutton").addClass("shelved");
        }
    });
});
$(function() {
    $(".buybox").on("click.dataTracking", "[data-tracking]", function() {
        var a = $(this).attr("data-tracking");
        if (a && (typeof ga !== "undefined")) {
            ga("bnetgtm.send", "pageview", a);
        }
    });
});