/* В©2013 Blizzard Entertainment, Inc. All rights reserved. */
"use strict";
var AccountSelectionUi = {
    form: null,
    continueButton: null,
    createButton: null,
    accountButtons: null,
    gameAccountIdInput: null,
    create: function() {
        this.form = $("#account-selection-form");
        this.continueButton = $("#upgrade-continue-button");
        this.accountButtons = $(
            "#eligible-account-list input[name=gameAccountIds]");
        this.gameAccountIdInput = $(
            "#account-selection-form input[name=gameAccountId]");
        this.gameAccountRegionInput = $(
            "#account-selection-form input[name=gameAccountRegion]"
        );
        this.upgradeInput = $(
            "#account-selection-form input[name=upgrade]");
        if (this.form.length && this.continueButton.length && this.accountButtons
                .length) {
            this.continueButton.on("click.accountSelection", $.proxy(
                this.postSelectedGameAccountId, this));
        }
        return this;
    },
    postSelectedGameAccountId: function() {
        var a = this.accountButtons.filter(":checked");
        if (a.length === 1) {
            this.gameAccountIdInput.val(a.attr(
                "data-bnet-shop-account-id"));
            this.gameAccountRegionInput.val(a.attr(
                "data-bnet-shop-account-region"));
            this.upgradeInput.val(a.attr("data-bnet-shop-upgrade"));
        }
        this.form.submit();
    }
};
var BillingAddressUi = {
    config: {},
    url: "/required-address-fields",
    form: $("#payment-form"),
    paymentType: $("#payment-option"),
    billingAddressBook: $("#address-book"),
    billingAddressFields: $("#billing-address-fields"),
    billingAddressCountry: $("#country"),
    billingAddressCity: $("#city"),
    billingAddressState: $("#state"),
    billingAddressPostalCode: $("#zipcode"),
    requiredFields: [],
    create: function(a) {
        this._construct(a);
        return this;
    },
    _construct: function(a) {
        if (!this.form.length || !this.billingAddressBook.length || !
                this.billingAddressFields.length) {
            return;
        }
        a = typeof a === "undefined" ? {} : a;
        this.config = $.extend({
            url: this.url
        }, a);
        this.setup();
    },
    setup: function() {
        var a = this.config;
        if (a.url !== "") {
            this.url = a.url;
        }
        this.currentCountry = this.billingAddressCountry.find("option")
            .filter(":selected").val();
        this.toggleAddressFields();
        this.registerEvents();
    },
    registerEvents: function() {
        this.form.on("change.billing", "#address-book", $.proxy(this._onChangeAddressBook,
            this));
        this.form.on("change.billing", "#country", $.proxy(this._onChangeCountry,
            this));
    },
    _onChangeAddressBook: function() {
        this.toggleAddressFields();
    },
    _onChangeCountry: function() {
        var a = this.billingAddressCountry.val();
        if (this.currentCountry !== a) {
            this.updateRequiredAddressFields(a);
        }
    },
    toggleAddressFields: function() {
        if (this.billingAddressBook.val() < 0) {
            this.billingAddressFields.removeClass("hide");
        } else {
            this.billingAddressFields.addClass("hide");
        }
    },
    updateRequiredAddressFields: function(d) {
        var c = document,
            b = c.location.toString(),
            a = this.paymentType.val() + "?country=" + d;
        if (this.url !== "") {
            $.ajax({
                url: this.url,
                dataType: "json",
                data: {
                    country: d
                },
                context: this
            }).done(function(e) {
                if (b.indexOf(a) < 0) {
                    if (this.requiredFields.join(",") !== e.requiredFields
                            .join(",")) {
                        this.billingAddressCountry.trigger(
                            "disable");
                        c.location = a;
                    }
                }
            });
        }
    }
};
var CreditCardData = {
    cardNumber: "",
    digits: 0,
    majorIndustryIdentifier: "",
    issuerIdentificationNumber: "",
    issuerCategory: [],
    issuerNetwork: "",
    luhnChecksum: 0,
    luhnValid: false,
    ISSUER_CATEGORIES: [
        ["isoTc68", "futureUse"],
        ["airlines"],
        ["airlines", "futureUse"],
        ["travel", "entertainment", "bankingFinancial"],
        ["bankingFinancial"],
        ["bankingFinancial"],
        ["merchandising", "bankingFinancial"],
        ["petroleum", "futureUse"],
        ["healthcare", "telecommunications", "futureUse"],
        ["national"]
    ],
    AMEX: "AX",
    VISA: "VI",
    DELTA: "DE",
    ELECTRON: "EL",
    MASTERCARD: "MC",
    DISCOVER: "DI",
    JCB: "JCB",
    SOLO: "SO",
    MAESTRO: "SW",
    create: function create(b) {
        var a = Object.create(this);
        a._construct.apply(a, arguments);
        return a;
    },
    _construct: function _construct(a) {
        this.update(a);
    },
    _super: function _super(b, a, c) {
        if (typeof a !== "string") {
            c = a;
            a = "_construct";
        }
        return Object.getPrototypeOf(b)[a].apply(this, c);
    },
    reset: function reset() {
        this.cardNumber = "";
        this.digits = 0;
        this.majorIndustryIdentifier = "";
        this.issuerIdentificationNumber = "";
        this.issuerCategory = [];
        this.issuerNetwork = "";
        this.luhnChecksum = 0;
        this.luhnValid = false;
    },
    update: function update(a) {
        if (typeof a === "undefined") {
            return false;
        }
        this.setCardNumber(a);
        this.setDigits();
        this.setMajorIndustryIdentifier();
        this.setIssuerIdentificationNumber();
        this.setIssuerCategory();
        this.setIssuerNetwork();
        this.setLuhnChecksum();
        this.setLuhnValid();
        return true;
    },
    setCardNumber: function setCardNumber(a) {
        this.reset();
        this.cardNumber = (a.toString()).replace(/[^0-9]/g, "");
    },
    setMajorIndustryIdentifier: function setMajorIndustryIdentifier() {
        this.majorIndustryIdentifier = this.digits > 0 ? this.cardNumber
            .charAt(0) : "";
    },
    setIssuerIdentificationNumber: function setIssuerIdentificationNumber() {
        this.issuerIdentificationNumber = this.digits > 5 ? this.cardNumber
            .substr(0, 6) : "";
    },
    setDigits: function setDigits() {
        this.digits = this.cardNumber.length;
    },
    setIssuerCategory: function setIssuerCategory() {
        this.issuerCategory = this.ISSUER_CATEGORIES[parseInt(this.majorIndustryIdentifier,
            10)] || [];
    },
    setIssuerNetwork: function setIssuerNetwork() {
        var b = this.cardNumber,
            c = "",
            a = /^3[47][0-9]{13}$/,
            e = /^6(?:011|5[0-9][0-9])[0-9]{12}$/,
            d =
                /^(?:2131|1800)[0-9]{11}|(?:35(?:28|29|[3-8][0-9]))[0-9]{12}$/,
            i =
                /^(?:(?:5(?:018|020|038|893))|(?:6(?:304|333|759|76[123]))|(?:0604)[0-9]{2}|(?:564182|633110))[0-9]{6,13}$/,
            f = /^5[1-5][0-9]{14}$/,
            g = /^6(?:334|767)[0-9]{12}(?:[0-9]{2,3})?$/,
            h = /^4[0-9]{12}(?:[0-9]{3})?$/;
        if (a.test(b)) {
            c = this.AMEX;
        } else {
            if (i.test(b)) {
                c = this.MAESTRO;
            } else {
                if (f.test(b)) {
                    c = this.MASTERCARD;
                } else {
                    if (h.test(b)) {
                        c = this.VISA;
                    } else {
                        if (e.test(b)) {
                            c = this.DISCOVER;
                        } else {
                            if (d.test(b)) {
                                c = this.JCB;
                            } else {
                                if (g.test(b)) {
                                    c = this.SOLO;
                                }
                            }
                        }
                    }
                }
            }
        }
        this.issuerNetwork = c;
    },
    setLuhnChecksum: function getLuhnChecksum() {
        var d = this.digits % 2,
            c = this.cardNumber,
            b = 0,
            a = this.digits - 1,
            e;
        if (a >= 0) {
            do {
                e = parseInt(c.charAt(a), 10);
                if (a % 2 === d) {
                    e *= 2;
                }
                b += (e > 9) ? e -= 9 : e;
            } while (a--);
        }
        this.luhnChecksum = b;
    },
    setLuhnValid: function isLuhnValid() {
        this.luhnValid = this.luhnChecksum % 10 === 0;
    }
};
var CreditCardUi = {
    config: {},
    url: null,
    form: $("#payment-form"),
    creditCardNumberField: $("#card-number"),
    creditCardTypeSelect: $("#card-type"),
    creditCardSecurityLabel: $("#verification-code-label"),
    creditCardData: null,
    useCreditCardData: true,
    creditCardTooltipCreated: false,
    create: function(a) {
        this._construct(a);
        return this;
    },
    _construct: function(a) {
        if (!this.form.length) {
            return;
        }
        a = typeof a === "undefined" ? {} : a;
        this.config = $.extend({
            url: "",
            productId: ""
        }, a);
        this.setup();
    },
    setup: function() {
        var a = this.config;
        if (a.url !== "") {
            this.url = a.url;
        }
        this.useCreditCardData = a.useCreditCardData ? (this.creditCardNumberField
            .length && this.creditCardTypeSelect.length && this.creditCardSecurityLabel
            .length) : false;
        this.creditCardData = CreditCardData.create(this.creditCardNumberField
            .val());
        this._onChangeCreditCardType();
        this.registerEvents();
    },
    registerEvents: function() {
        if (this.useCreditCardData) {
            this.form.on("input.creditcard propertychange.creditcard",
                "#card-number", $.proxy(this._onChangeCreditCardNumber,
                    this));
        }
        this.form.on("change.creditcard", "#card-type", $.proxy(this._onChangeCreditCardType,
            this));
        this.form.on("blur.creditcard", "#select-box-card-type", $.proxy(
            this._onBlurCreditCardType, this));
    },
    _onChangeCreditCardNumber: function(a) {
        this.creditCardData.update(a.currentTarget.value);
        this.setCardType(this.creditCardData.issuerNetwork);
    },
    _onChangeCreditCardType: function() {
        this.setSecurityCode(this.creditCardTypeSelect.find(":selected")
            .val());
    },
    _onBlurCreditCardType: function() {
        if (this.creditCardTypeSelect[0].value === "") {
            this.enableCreditCardTypeDetection();
        } else {
            this.disableCreditCardTypeDetection();
        }
    },
    disableCreditCardTypeDetection: function() {
        this.useCreditCardData = false;
        this.form.off("input.creditcard propertychange.creditcard",
            "#card-number");
    },
    enableCreditCardTypeDetection: function() {
        this.useCreditCardData = true;
        this.form.on("input.creditcard propertychange.creditcard",
            "#card-number", $.proxy(this._onChangeCreditCardNumber,
                this));
    },
    setCardType: function(a) {
        if (a !== "" && this.creditCardTypeSelect.length) {
            this.creditCardTypeSelect.find('[value="' + a + '"]').trigger(
                "click");
        } else {
            this.creditCardTypeSelect.find("option").filter(":first").trigger(
                "click");
        }
    },
    setSecurityCode: function(e) {
        var d = typeof Msg !== "undefined" ? Msg.payment : null,
            h, i = document,
            l = i.createElement("div"),
            c = i.createElement("p"),
            j, f = new Image(),
            a = "",
            g = this.creditCardSecurityLabel.parent(),
            b = this.creditCardData,
            k = this;
        l.textContent = "";
        c.className = "csc-text";
        f.width = 160;
        f.height = 100;
        f.alt = "";
        f.className = "csc-image";
        if (this.creditCardSecurityLabel.length && d) {
            if (e === b.VISA || e === b.ELECTRON || e === b.SOLO) {
                h = d.securityCvv2Label;
                j = d.securityCvv2Note;
                a = d.securityCvv2Image;
            } else {
                if (e === b.MASTERCARD || e === b.MAESTRO) {
                    h = d.securityCvc2Label;
                    j = d.securityCvc2Note;
                    a = d.securityCvc2Image;
                } else {
                    if (e === b.DISCOVER) {
                        h = d.securityCid3Label;
                        j = d.securityCid3Note;
                        a = d.securityCid3Image;
                    } else {
                        if (e === b.AMEX) {
                            h = d.securityCid4Label;
                            j = d.securityCid4Note;
                            a = d.securityCid4Image;
                        } else {
                            if (e === b.JCB) {
                                h = d.securityCav2Label;
                                j = d.securityCav2Note;
                                a = d.securityCav2Image;
                            } else {
                                h = d.securityLabel;
                                j = d.securityNote;
                                a = d.securityImage;
                            }
                        }
                    }
                }
            }
            this.creditCardSecurityLabel.text(h);
            c.appendChild(i.createTextNode(j));
            l.appendChild(c);
            if (a !== "") {
                f.src = a;
                l.appendChild(f);
            }
            if (k.creditCardTooltipCreated) {
                g.tooltipster("content", $(l));
            } else {
                g.tooltipster({
                    content: $(l),
                    delay: 0,
                    position: "right",
                    timer: 5000,
                    trigger: "click",
                    functionReady: function(m, n) {
                        k.creditCardTooltipCreated = true;
                    }
                });
            }
        }
    }
};
var PaymentUi = {
    config: {},
    submitState: 0,
    form: $("#payment-form"),
    agreement: $("#agreement"),
    submitButton: $("#payment-submit"),
    cancelButton: $("#payment-cancel"),
    savePayment: $("#save-payment"),
    paymentTypeSelect: $("#payment-option"),
    billingAddressFields: $("#billing-address-fields"),
    addressBookSelect: $("#address-book"),
    partialPayment: $("#partial-payment"),
    partialPaymentSummary: $("#checkout-total-balance-applied"),
    totalCost: $("#total-cost"),
    totalCostWithPartial: $("#total-cost-with-partial-payment"),
    addressTrackingType: "",
    create: function(a) {
        this.init(a);
        return this;
    },
    init: function(a) {
        if (this.form.length === 0) {
            return;
        }
        this.config = $.extend({
            addressTrackingType: ""
        }, a || {});
        this.setup();
    },
    setup: function() {
        var a = this.config;
        this.addressTrackingType = a.addressTrackingType;
        if (this.agreement.length && (Boolean(this.agreement[0].checked) ===
            false)) {
            this.disableSubmitButton();
        }
        this.refreshPartialPayment();
        this.partialPayment.on("click.partialPayment", $.proxy(this.refreshPartialPayment,
            this));
        this.registerEvents();
    },
    registerEvents: function() {
        $(window).on("pageshow", $.proxy(this._onPageShow, this));
        this.form.on("submit", $.proxy(this._onFormSubmit, this));
        this.form.on("beforeSubmit", $.proxy(this._onBeforeSubmit, this));
        this.form.on("change", "#agreement", $.proxy(this._onChangeAgreement,
            this));
        this.form.on("change", "#payment-option", $.proxy(this._onChangePaymentType,
            this));
        this.form.on("enableSubmit", $.proxy(this.enableSubmitButton,
            this));
        this.form.on("disableSubmit", $.proxy(this.disableSubmitButton,
            this));
    },
    _onPageShow: function(a) {
        if (a.originalEvent.persisted) {
            this.submitButton.button("reset");
        }
    },
    _onBeforeSubmit: function() {},
    _analyticsCalls: function() {
        var c = $.Deferred();
        try {
            var f = window.setTimeout(function() {
                c.reject("Analytics timed out");
            }, 2000);
            var a = function() {
                d -= 1;
                if (d === 0) {
                    window.clearTimeout(f);
                    c.resolve();
                }
            };
            var b = [];
            var d = 0;
            b.push({
                eventCategory: this._pushEventPrefix(
                    "Shop - Payment Submit"),
                eventAction: "submit",
                hitCallback: a
            });
            if (this.addressBookSelect.val() === "-1" && this.billingAddressFields
                    .length && this.addressTrackingType !== "") {
                b.push({
                    eventCategory: this._pushEventPrefix(
                        "Shop - Address Information"),
                    eventAction: "Enter address",
                    eventLabel: this.addressTrackingType,
                    hitCallback: a
                });
            }
            if (this.savePayment.length) {
                b.push({
                    eventCategory: this._pushEventPrefix(
                        "Shop - Payment Method"),
                    eventAction: "Save payment",
                    eventLabel: this.savePayment.prop("checked") ?
                        "opt in" : "opt out",
                    hitCallback: a
                });
            }
            d = b.length;
            while (b.length > 0) {
                ga("bnetgtm.send", "event", b.pop());
            }
        } catch (g) {
            c.reject(g);
        }
        return c.promise();
    },
    _onFormSubmit: function() {
        if (this.submitState === 1) {
            return false;
        } else {
            if (this.submitState === 2) {
                this.submitState = 1;
                return true;
            }
        }
        this.submitState = 1;
        this.submitButton.button("loading");
        this.disableCancelButton();
        this.form.trigger("beforeSubmit");
        $.when(this._analyticsCalls()).always($.proxy(function() {
            this.submitState = 2;
            this.form.submit();
        }, this));
        return false;
    },
    _onChangePaymentType: function() {
        this.loadNewPaymentForm(document.location.toString(), this.paymentTypeSelect
            .val());
    },
    loadNewPaymentForm: function(b, a) {
        if (b.indexOf(a) < 0) {
            this.paymentTypeSelect.trigger("disable");
            document.location = a;
        }
    },
    _onChangeAgreement: function() {
        if (this.agreement[0].checked) {
            this.enableSubmitButton();
        } else {
            this.disableSubmitButton();
        }
    },
    enableSubmitButton: function() {
        this.submitState = 0;
        this.submitButton.prop("disabled", false).removeAttr("disabled")
            .removeClass("disabled");
    },
    disableSubmitButton: function() {
        this.submitState = 1;
        this.submitButton.prop("disabled", true).attr("disabled",
            "disabled").addClass("disabled");
    },
    disableCancelButton: function() {
        this.cancelButton.prop("disabled", true).attr("disabled",
            "disabled").addClass("disabled");
    },
    refreshPartialPayment: function() {
        if (this.partialPayment.length) {
            if (this.partialPayment.prop("checked")) {
                this.partialPaymentSummary.removeClass("hide");
                this.totalCost.addClass("hide");
                this.totalCostWithPartial.removeClass("hide");
            } else {
                this.partialPaymentSummary.addClass("hide");
                this.totalCost.removeClass("hide");
                this.totalCostWithPartial.addClass("hide");
            }
        }
    },
    _pushEventPrefix: function(a) {
        if (typeof Msg === "object" && Msg.analytics.eventPrefix !== "") {
            return Msg.analytics.eventPrefix + " " + a;
        }
        return a;
    }
};
var PaymentHelperKr = {
    otherPaymentFields: $(".purchase-section input"),
    config: {
        requireActiveX: true,
        activeXRequiredTooltip: ""
    },
    create: function(a) {
        this.init(a);
        return this;
    },
    init: function(a) {
        a = typeof a !== "undefined" ? a : {};
        this.config = $.extend(a, {});
        this.setup();
    },
    setup: function() {
        var a = (Object.getOwnPropertyDescriptor && Object.getOwnPropertyDescriptor(
                window, "ActiveXObject")) || ("ActiveXObject" in window);
        if (this.config.requireActiveX) {
            if (a) {
                if (PaymentUi.agreement.length === 0) {
                    PaymentUi.form.trigger("enableSubmit");
                }
                if (typeof(CommonActiveXChecker) !== "undefined") {
                    PaymentUi.form.on("beforeSubmit", function() {
                        CommonActiveXChecker.submitCheck();
                    });
                }
            } else {
                this.activeXWarning = $("#activex-warning");
                this.activeXWarning.removeClass("hide");
                PaymentUi.form.trigger("disableSubmit");
                PaymentUi.agreement.prop("disabled", true);
                this.otherPaymentFields.prop("disabled", "disabled");
            }
        }
    }
};
var SuccessOffsitePoll = function(a) {
    a = a || {};
    this.startDelay = a.startDelay || 15;
    this.timeout = a.timeout || 600;
    this.onsuccess = a.onsuccess;
    this.onerror = a.onerror;
    this.onstop = a.onstop;
    if (a.token && a.pollUrl) {
        this.token = a.token;
        this.pollUrl = a.pollUrl;
        this.start = new Date();
        this.timer = window.setTimeout($.proxy(this.poll, this), this.startDelay *
        1000);
    }
};
SuccessOffsitePoll.prototype.poll = function() {
    var a = this;
    a.timer = null;
    $.get(a.pollUrl + a.token, null, function(d, e, c) {
        if (d && (d.invoiceStatus === "QUEUED" || d.invoiceStatus ===
            "PENDING")) {
            var b = new Date() - a.start;
            if (b > (a.timeout * 1000)) {
                window.setTimeout($.proxy(a.stop, a), 0);
            } else {
                a.timer = window.setTimeout($.proxy(a.poll, a), d.frequency);
            }
        } else {
            if (d && (d.invoiceStatus === "ERROR" || d.invoiceStatus ===
                "UNKNOWN")) {
                if ($.isFunction(a.onerror)) {
                    window.setTimeout($.proxy(a.onerror, a), 0);
                }
            } else {
                if ($.isFunction(a.onsuccess)) {
                    window.setTimeout($.proxy(a.onsuccess, a), 0);
                }
            }
        }
    }, "json").fail(function() {
        if ($.isFunction(a.onerror)) {
            window.setTimeout($.proxy(a.onerror, a), 0);
        }
    });
};
SuccessOffsitePoll.prototype.stop = function() {
    if (this.timer) {
        window.clearTimeout(this.timer);
        this.timer = null;
    }
    if ($.isFunction(this.onstop)) {
        window.setTimeout($.proxy(this.onstop, this), 0);
    }
};
var TaxData = {
    config: {},
    container: null,
    productId: "",
    url: "/checkout/tax/",
    _city: "",
    _state: "",
    _zipcode: "",
    _country: "",
    _tax: null,
    create: function create() {
        var a = Object.create(this);
        a._construct.apply(a, arguments);
        return a;
    },
    _construct: function(b, a) {
        if (typeof b === "undefined") {
            return;
        }
        this.productId = b;
        a = typeof a === "undefined" ? {} : a;
        this.config = $.extend({
            url: "",
            query: ""
        }, a);
        this.setup();
    },
    _super: function(b, a, c) {
        if (typeof a !== "string") {
            c = a;
            a = "_construct";
        }
        return Object.getPrototypeOf(b)[a].apply(this, c);
    },
    setup: function() {
        var a = this.config;
        if (a.url !== "") {
            this.url = a.url;
        }
        this.container = $(a.query);
        if (!this.container.length) {
            this.container = $("body");
        }
    },
    updateTaxInfo: function(f, d, c, e) {
        var a = this.container,
            b = this.productId;
        if (this.useCache(f, d, c, e)) {
            a.trigger("update.tax", [this._tax, b]);
        } else {
            if (this.hasRequiredParameters(f, d, c, e)) {
                $.ajax({
                    url: this.url + this.productId,
                    dataType: "json",
                    context: this,
                    data: {
                        city: f,
                        state: d,
                        zipcode: c,
                        country: e
                    }
                }).done(function(h) {
                    var g = h.tax;
                    this.populateCache(f, d, c, e, g);
                    if (this.hasRequiredParameters(f, d, c, e,
                            g)) {
                        a.trigger("update.tax", [g, b]);
                    } else {
                        a.trigger("update.tax", [null, b]);
                    }
                }).fail(function() {
                    a.trigger("update.tax", [null, b]);
                });
            }
        }
    },
    hasRequiredParameters: function(d, b, a, c) {
        return d !== "" && b !== "" && a !== "" && c !== "";
    },
    useCache: function(d, b, a, c) {
        return this.hasRequiredParameters(d, b, a, c) && this._city ===
            d && this._state === b && this._zipcode === a && this._country ===
            c;
    },
    populateCache: function(e, c, b, d, a) {
        if (this.hasRequiredParameters(e, c, b, d)) {
            this._city = e;
            this._state = c;
            this._zipcode = b;
            this._country = d;
            this._tax = a;
        }
    }
};
var TaxUi = {
    config: {},
    url: null,
    productId: "",
    taxTipContent: "",
    form: $("#payment-form"),
    billingAddressBook: $("#address-book"),
    heading: $("#total-heading"),
    total: $("#total-cost"),
    afterTax: $("#after-taxes"),
    beforeTax: $("#before-taxes"),
    salesTaxableHeading: $(".additional-sales-tax-eligible"),
    billingAddressCity: $("#city"),
    billingAddressState: $("#state"),
    billingAddressPostalCode: $("#zipcode"),
    billingAddressCountry: $("#country"),
    cost: 0,
    taxData: null,
    currency: "",
    currencyFormat: "",
    taxEnabled: "",
    taxApplied: "",
    taxRemoved: "",
    create: function(a) {
        this._construct(a);
        return this;
    },
    _construct: function(a) {
        if (!this.form.length) {
            return;
        }
        if (!this.total.length) {
            return;
        }
        a = typeof a === "undefined" ? {} : a;
        this.config = $.extend({
            url: "",
            productId: "",
            taxTipContent: ""
        }, a);
        this.setup();
    },
    setup: function() {
        var a = this.config;
        if (a.url !== "") {
            this.url = a.url;
        }
        this.productId = a.productId;
        this.taxTipContent = a.taxTipContent;
        if (typeof Msg !== "undefined") {
            this.currency = Msg.tax.currency || "";
            this.currencyFormat = Msg.tax.currencyFormat || "";
            this.taxEnabled = Msg.tax.enabled || "";
            this.taxApplied = Msg.tax.applied || "";
            this.taxRemoved = Msg.tax.removed || "";
        }
        this.cost = parseFloat(this.total[0].getAttribute(
            "data-base-cost"));
        this.taxData = TaxData.create(this.productId, {
            url: this.url,
            query: "#address-book"
        });
        this.registerEvents();
    },
    registerEvents: function() {
        this.form.on("update.tax", "#address-book", $.proxy(this._onUpdateTax,
            this));
        this.form.on("change.tax", "#address-book", $.proxy(this._onChangeAddressBook,
            this));
        this.form.on("change.tax", "#city", $.proxy(this._onChangeBillingAddress,
            this));
        this.form.on("change.tax", "#state", $.proxy(this._onChangeBillingAddress,
            this));
        this.form.on("change.tax", "#zipcode", $.proxy(this._onChangeBillingAddress,
            this));
        this.form.on("change.tax", "#country", $.proxy(this._onChangeBillingAddress,
            this));
        this.billingAddressBook.trigger("change.tax");
        this.salesTaxableHeading.tooltipster({
            content: this.taxTipContent,
            contentAsHTML: true,
            interactive: true,
            position: "right",
            speed: 0,
            trigger: "click"
        });
    },
    _onUpdateTax: function(c, b, a) {
        if (this.productId === a) {
            this.updateTaxHeading(b);
            this.updateTotal(b);
        }
    },
    _onChangeAddressBook: function() {
        var a = this.billingAddressBook,
            e, c, b, d;
        if (a.val() < 0) {
            this.updateTaxInfoFromAddress();
        } else {
            a = a.find(":selected");
            e = a[0].getAttribute("data-tax-city");
            c = a[0].getAttribute("data-tax-state");
            b = a[0].getAttribute("data-tax-zipcode");
            d = a[0].getAttribute("data-tax-country");
            this.taxData.updateTaxInfo(e, c, b, d);
        }
    },
    _onChangeBillingAddress: function() {
        this.updateTaxInfoFromAddress();
    },
    updateTaxInfoFromAddress: function() {
        this.taxData.updateTaxInfo(this.billingAddressCity.val(), this.billingAddressState
            .val(), this.billingAddressPostalCode.val(), this.billingAddressCountry
            .val());
    },
    updateTaxHeading: function(a) {
        var d = this.heading,
            b = this.beforeTax,
            c = this.afterTax;
        if (typeof a !== "number") {
            d.attr("data-original-title", this.taxEnabled);
            c.addClass("hide");
            b.removeClass("hide");
        } else {
            if (a > 0) {
                d.attr("data-original-title", this.taxApplied.replace(
                    "{0}", this.currencyFormat.replace("{0}", a
                        .toFixed(2))).replace(/(<([^>]+)>)/ig,
                    ""));
                c.removeClass("hide");
                b.addClass("hide");
            } else {
                d.attr("data-original-title", this.taxRemoved);
                c.addClass("hide");
                b.addClass("hide");
            }
        }
    },
    updateTotal: function(a) {
        this.total.html(this.currencyFormat.replace("{0}", (this.cost +
        a).toFixed(2)));
    }
};