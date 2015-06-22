var FormHandler = {
    animationCallback: null,
    PAYMENT_TOGGLE_CONTAINER: "#payment-toggle", // level 1
    PAYMENT_SECONDARY_DETAILS_CONTAINER: "#secondary-payment-details", // level 2
    PAYMENT_DETAILS_CONTAINER: "#payment-details", // level 3
    DESCRIPTION_CONTAINER_ID: "desc-container",
    selectLevel: null,
    lastSelected: false,
    loadingCompleted: false,
    level1Option: 0,
    level2Option: 0,

    /*
     * Kickoff method triggered by "Pay with" selectbox.
     * @param resourceURL string							URL to Ajax resource
     * @param callbackContainerElement string			container used for Ajax success callback
     */
    paymentSelect: function (element) {
        window.location.hash = "";

        h.loadingCompleted = false;

        var resourceURL = element.value || element;

        if (element.id == "payment-method") {
            h.selectLevel = 1;
        }else if (element.id == "secondary-payment-method") {
            h.selectLevel = 2;
        }else{
            h.selectLevel = 3;
        }

        getTax.accountBalancePageLoadCount = -1;
        getTax.flagTaxLoad = 0;

        // Check if "New Payment Method" is clicked or if we want to display a new payment method
        h.loadPayment(resourceURL);

        h.highlight(element);
        h.toggleDescription(element);

        $(".alert.error").hide(); //hide error

        // reset error
        getTax.accountBalancePartialError = false;
    },

    loadPayment: function (resourceURL) {
        if (resourceURL) {
            resourceURL = resourceURL.replace("&amp;", "&");
            switch (h.selectLevel) {
                case 1:
                    h.animationCallback = function () {h.populatePaymentForm(resourceURL, h.PAYMENT_TOGGLE_CONTAINER);};
                    $(h.PAYMENT_TOGGLE_CONTAINER).animate({ opacity: 0 }, 0, "linear", h.animationCallback);
                    break;
                case 2:
                    h.animationCallback = function () {h.populatePaymentForm(resourceURL, h.PAYMENT_SECONDARY_DETAILS_CONTAINER);};
                    $(h.PAYMENT_SECONDARY_DETAILS_CONTAINER).animate({ opacity: 0 }, 0, "linear", h.animationCallback);
                    break;
                case 3:
                    h.animationCallback = function () {h.populatePaymentForm(resourceURL, h.PAYMENT_DETAILS_CONTAINER);};
                    $(h.PAYMENT_DETAILS_CONTAINER).animate({ opacity: 0 }, 0, "linear", h.animationCallback);
                    break;
            }
        }
    },

    /*
     * Pulls in selected payment method form or payment information on file
     * @param string resourceURL 			URL to Ajax resource
     * @param string targetElement 			container used for Ajax success callback
     * Note: both params passed in from paymentSelect()
     */
    populatePaymentForm: function (resourceURL, targetElement) {
        $.ajax({
            url: resourceURL + '&_=' + new Date().getTime(),
            dataType: "html",
            beforeSend: function() {

                $("#payment-toggle").hide();
                $("#payment-toggle-loading").show();
                if (h.selectLevel == 3) {
                    $("#payment-details").empty();
                }
            },
            success: function (data) {
                $("#payment-toggle").show();
                $("#payment-toggle-loading").hide();
                $(targetElement).html(data);
                FormHandler.toggleAddressForm($('#billingAddress\\.storedAddressId :selected').val());

                $(targetElement)
                    .delay(200)
                    .animate(
                    { opacity: 1 }, 200, 'linear', function () { h.loadingCompleted = true; }
                );

                UI.initialize(targetElement);
                Core.ui();

                //Cancel linked
                FormHandler.setCancelLink();

                h.pngFix();

                // Hide totals if the user selects a giftcode
                if (resourceURL.indexOf("GIFTCODE") != -1) {
                    $(".total-due-submit").hide();
                    $(".total-due").hide();
                } else {
                    $(".total-due-submit").show();
                    $(".total-due").show();
                }

                if (h.selectLevel == 2) {
                    $("#selectLevel1").hide();
                    $("#balance-info").show();
                    getTax.flagAccountBalancePage++;
                }else{
                    getTax.sufficientFunds = false;
                    getTax.flagAccountBalancePage = 0;
                }

                if (resourceURL.indexOf("CreditCardWalletEntry") != -1) {
                    getTax.secondPaymentMethod= 'CustomerWallet';
                    if (h.selectLevel != 1 && ($(h.PAYMENT_SECONDARY_DETAILS_CONTAINER).is(":visible"))) {
                        getTax.flagPaymentMethod = 'accountBalance';
                    }else{
                        getTax.flagPaymentMethod = 'CustomerWallet';
                    }
                } else if(resourceURL.indexOf("NewPayment") != -1){
                    getTax.secondPaymentMethod= 'NewPayment';
                    if (h.selectLevel != 1 && ($(h.PAYMENT_SECONDARY_DETAILS_CONTAINER).is(":visible"))) {
                        getTax.flagPaymentMethod = 'accountBalance';
                    }else{
                        getTax.flagPaymentMethod = 'otherPayment';
                    }
                } else if (resourceURL.indexOf("PaypalWalletEntry") != -1) {
                    getTax.secondPaymentMethod= 'PaypalWalletEntry';
                    getTax.flagPaymentMethod = 'PayPal';
                }

                if (getTax.accountBalancePartialError) {
                    getTax.flagPaymentMethod = 'accountBalance'
                }

                var paymentMethodValue = $("#payment-method").val();

                if (paymentMethodValue && paymentMethodValue.indexOf("EBANK") != -1 && Core.region != 'tw') {
                    $("#selectLevel1").hide();
                    $("#balance-info").show();
                    // reset tax loaded status if using ebalance
                    getTax.flagTaxLoad = 0;
                }else{
                    $("#selectLevel1").show();
                    $("#balance-info").hide();
                }

                if (getTax.accountBalanceErrorPage == null) {
                    if (getTax.accountBalanceErrorPageSub != null) {
                        getTax.accountBalancePageLoadCount += 2;
                    }
                    if (getTax.accountBalanceError || getTax.flagAccountBalancePage < 2) {
                        getTax.accountBalanceError = false;
                        getTax.initialize();
                        getTax.accountBalancePageLoadCount++;
                    }
                    getTax.accountBalancePartialError  = false;
                    getTax.accountBalanceError = false;
                }else{
                    getTax.accountBalanceErrorPage  = getTax.accountBalanceErrorPage.replace("&amp;", "&");
                    if (resourceURL == getTax.accountBalanceErrorPage) {
                        getTax.accountBalanceError = false;
                        getTax.accountBalancePageLoadCount=0;
                        getTax.accountBalanceErrorPage = null;
                        getTax.initialize();
                        getTax.accountBalancePageLoadCount += 2;
                    }else{
                        if(resourceURL.indexOf("PaymentSection=NewPayment") != -1){
                            h.selectLevel = 3;
                            level2Option = FormHandler.getResourceVars(getTax.accountBalanceErrorPageSub);
                            resourceURL = getTax.accountBalanceErrorPageSub;
                            h.loadPayment(getTax.accountBalanceErrorPageSub);
                        }else{
                            h.selectLevel = 2;
                            level1Option = FormHandler.getResourceVars(getTax.accountBalanceErrorPage);
                            $("#secondary-payment-method option")[level1Option["PaymentSectionOptionIndex"]].selected = true;
                            h.loadPayment(getTax.accountBalanceErrorPage);
                            getTax.accountBalanceErrorPage = getTax.accountBalanceErrorPageSub;
                        }
                        getTax.accountBalancePageLoadCount += 2;
                    }
                }
            }
        });
    },

    changeMethod: function(option){
        $("#balance-info").hide();
        $("#selectLevel1").show();
        $(".alert.error").slideUp(200); //hide error
        if (option != null) {
            $("#payment-method option")[option].selected = true;
            this.paymentSelect($("#payment-method")[option]);
        }
        getTax.flagTaxLoad = 0;
    },

    changeMethodFake: function(element){
        $(".alert.error").slideUp(200); //hide error
        if(element){
            this.paymentSelect(element);
        }else{
            this.paymentSelect(document.getElementById("payment-method"));
        }
    },


    /*
     *	Toggle display of address form.
     * 	@param index integer
     */
    toggleAddressForm: function(index) {
        if (index < 0) {
            $('.billing-information').show();
        } else {
            if(Core.region == "us" && index){
                getTax.getTaxInfoByShippingAddress(index);
            }
            $('.billing-information').hide();
        }
    },

    /*
     *	Toggle submit button on accept/unaccept terms
     * 	@param domElement submitParentElement
     */
    acceptTerms: function (submitParentElement) {
        var $submitElement = $('button:submit', submitParentElement),
            isTermsChecked = $("#terms-accept").is(":checked");

        if ($submitElement){
            $submitElement = $('#submitted', submitParentElement);
        }

        if (Core.region === 'kr') {
            var isPaymentNoChecked = false;

            $('.terms-notice input:checkbox', submitParentElement).each(function (index) {
                var isChecked = $(this).is(":checked"),
                    $lableElement = $('.terms-notice label').eq(index);

                if (isChecked) {
                    $lableElement.addClass("text-green");
                } else {
                    $lableElement.removeClass("text-green");
                    isPaymentNoChecked = true;
                }
            });

            if (!isPaymentNoChecked) {
                UI.wakeButton($submitElement);
                return;
            } else {
                UI.freezeButton($submitElement);
                return;
            }
        } else {
            $('.terms-notice label', submitParentElement)
                .toggleClass("text-green", isTermsChecked);

            if (isTermsChecked) {
                UI.wakeButton($submitElement);
                return;
            }
        }
    },

    /*
     *	Toggle submit button on complate input rule
     * 	@param domElement submitParentElement
     */
    acceptRule: function () {
        var cardNo = $("#cardNo"),
            cardNoValue = cardNo.val(),
            termsAccept = $("#terms-accept"),
            fields = [cardNo[0]];
        if (termsAccept[0]) {
            fields[1]=termsAccept[0];
        }

        var complete = true;

        if (cardNo) {
            var $submitElement = $("#prepaid-button");
        }

        if(typeof $submitElement  == "undefined"||!$submitElement){
            return;
        }
        // Get event from Input fields

        if (cardNo.length > 0) {
            $(fields).bind('keyup change', {}, function(e) {
                cardNoValue = cardNo.val()
                complete = true;

                if (termsAccept[0] && !termsAccept[0].checked) {
                    complete = false;
                }

                if (cardNoValue.length < 13) {
                    complete = false;
                }

                if (typeof $submitElement == "undefined" || !$submitElement) {
                    return;
                }

                if (complete) {
                    UI.wakeButton($submitElement);
                    return;
                }

                UI.freezeButton($submitElement);
            });
            if (cardNoValue.length == 25) {
                if (complete) {
                    UI.wakeButton($submitElement);
                    return;
                }
            }
        }
    },

    /*
     *	Only send form when required fields populated and TOU accepted
     * 	@param string linkClass
     * 	@param domElement parentForm
     * 	@param string processingString
     * 	@param string disabledClass
     */
    handleSubmit: function (linkClass, parentForm, processingStr, disabledClass) {
        var errors = [],
            requiredFields = $("#payment-form [required]:visible"),
            termsAgreement = $("#terms-accept"),
            missingRequiredFields = null,
            iban = $(".variable-inputs.iban-active").length,
            twTaxRadios = $("#payment-form input:radio[name=twTaxInvoice]");

        requiredFields.parents("div.form-row").removeClass("form-error");

        if (requiredFields.length) {
            if (iban) {
                missingRequiredFields = requiredFields.filter(function() {
                    return !this.value && this.id != "bankCode" && this.id != "branchCode" && this.id != "bankCheckDigit" && this.id != "bankAccountNumber"
                        && this.name != "twTaxInvoice";
                });
            } else {
                missingRequiredFields = requiredFields.filter(function() {
                    return !this.value && this.id != "iban" && this.name != "twTaxDonate";
                });
            }

            if (missingRequiredFields.length) {
                errors.push(FormMsg.fieldsMissing);
                missingRequiredFields.parents("div.form-row").addClass("form-error");
            }

            if (twTaxRadios.length) {
                if (twTaxRadios.filter(":checked").length == 0) {
                    errors.push(FormMsg.taxInvoiceSelect);
                }
            }
        }

        if (termsAgreement.filter(":visible").length && !termsAgreement[0].checked) {
            errors.push(FormMsg.tosDisagree);
            termsAgreement.siblings("label").addClass("text-red");
        } else {
            termsAgreement.siblings("label").removeClass("text-red");
        }

        if (errors.length) {
            // Hide previous error messages
            $(".payment-overview .alert").hide();

            // Inject new errors
            $(".payment-overview").prepend(this.makeErrorBox(errors));
            window.scrollTo(0, 0);

            // Prevent submission of bad data
            return false;
        } else {
            // Set submit button to "processing" state to prevent multi-submit
            var $submitButton = $("#submitted, #submit-direct-debit");
            if ($submitButton.length) {
                UI.processButton($submitButton);
            }

            // Disable cancel button
            $("#edit-order-link, .ui-cancel")
                .attr("href", "#")
                .attr("onclick", "return false;")
                .css("color", "#bdbcb9");

            // Submit data
            return true;
        }
    },

    /*
     *	Convinience function; get URL params
     * 	@param string resourceURL
     * 	@return array
     */
    getResourceVars: function (resourceURL) {
        resourceURL = resourceURL.replace("&amp;", "&");
        var pairs = [], hash;
        var hashes = resourceURL.slice(resourceURL.indexOf('?') + 1).split('&');

        for(var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            pairs.push(hash[0]);
            pairs[hash[0]] = hash[1];
        }
        return pairs;
    },

    /*
     *	Handle selection highlighting
     * 	@param domElement element
     */
    highlight: function (element) {

        $(element).closest("ul").find("li").removeClass("active");
        $(element).closest("li").addClass("active");
        $("#" + h.DESCRIPTION_CONTAINER_ID).remove();
    },

    /*
     *	Handles specific payment description texts
     * 	@param domElement element
     */
    toggleDescription: function (element) {

        if ($("#" + h.DESCRIPTION_CONTAINER_ID).is(":visible")) {
            $("#" + h.DESCRIPTION_CONTAINER_ID).remove();
        }

        if($(element).closest("li").find("p").size() == 0) return;

        var $parentList = $(element).parents("li:first");
        var content = $parentList.find("p").html();

        // Get relevant input.radio
        var $inputElement = $parentList.find("input");
        $inputElement[0].checked = "checked";

        // Do Ajax
        /*h.paymentSelect($inputElement[0]);	*/

        // Create LI element holding description
        var $contentWrapper = $('<li />', {
            id: h.DESCRIPTION_CONTAINER_ID
        }).append($("<p />").html(content)).show();

        var $appendToElement = ($parentList.hasClass("last-on-row")) ? $parentList : $parentList.nextAll(".last-on-row:first");

        // Append newly created LI element to last LI.last-on-row in row
        $appendToElement.after($contentWrapper);

    },
    pngFix: function () {
        if ($.browser.msie && $.browser.version < 8) {
            $("#payment-types").pngFix();
            $(".card-code").pngFix();
            $(".thumb").pngFix();
        }
    },

    /*
     *	set cancel button href from backlink
     * 	@param setCancelLink
     */
    setCancelLink: function () {
        if(typeof(PaymentData) !== "undefined"){
            if(Core.region == "cn" || Core.region == "tw" || Core.region == "kr"){
                PaymentData.backlink = PaymentData.backlink.replace("S2_TIME","S2")
            }            $('#payment-cancel').attr("href", PaymentData.backlink.replace("&amp;","&"));        }
    },

    makeErrorBox: function(errorMsgs) {
        $('#content > .alert').remove();
        var errorCount = errorMsgs.length;
        var errorHtml = ''
            + '<div class="alert error closeable border-4 glow-shadow" style="display:block;">'
            + '<div class="alert-inner">'
            + '<div class="alert-message">'
            + '<p class="title"><strong><a name="form-errors"> </a>' + FormMsg[((errorCount>1) ? "headerMultiple" : "headerSingular")] + '</strong></p>';
        if (errorCount > 1) {
            errorHtml += '<ul>';
            for (var i=0; i<errorCount; i++) {
                errorHtml += '<li>' + errorMsgs[i] + '</li>';
            }
            errorHtml += '</ul>';
        } else {
            errorHtml += '<p>' +errorMsgs[0]+ '</p>';
        }
        errorHtml += ''
        +'</div>'
        + '</div>'
        + '</div>';
        return errorHtml;
    }

};
var h = FormHandler;


$(function(){
    $(".alert.error").eq(0).show();
    PrepaidRedeem.create('#ebank_redeem_form');
    //cancel linked
    FormHandler.setCancelLink();
    FormHandler.highlight($("#payment-types").find("input:checked"));

    /*for KR activeX refresh issue*/
    var currentPaymentMethod = window.location.hash.replace("#", "");
    if(currentPaymentMethod=="KR_BANK_TRANSFER" || currentPaymentMethod=="KR_ARS" || currentPaymentMethod=="KR_CREDIT_CARD"
        || currentPaymentMethod=="KR_GIFT_CERT_B" || currentPaymentMethod=="KR_GIFT_CERT_C"){
        var currentPaymentMethodObj = document.getElementById(currentPaymentMethod);
        currentPaymentMethodObj.checked=true;
        FormHandler.paymentSelect(currentPaymentMethodObj);
        $(".alert.error").eq(0).show();
    }
});

var PrepaidRedeem = {
    config: {},

    query: '',

    form: null,
    cardEntry: null,
    termsCheckbox: null,
    submitButton: null,

    /**
     * Initialize the PrepaidRedeem class (as singleton) and apply configuration (if supplied)
     *
     * @param [query]	CSS selector for the containing form
     * @param [config]	Configuration
     */
    create: function(query, config) {
        query = typeof query === 'undefined' ? '#ebank_redeem_form' : query;
        config = typeof config === 'undefined' ? {} : config;

        this.form = $(query);
        if (!this.form.length) {
            return;
        }

        this.config = $.extend({
            cardEntryQuery: '#cardNo',
            termsCheckboxQuery: '#terms-accept',
            submitButtonQuery: '#prepaid-button'
        }, config);

        this.setup();
    },

    /**
     * Sets up the PrepaidRedeem class
     */
    setup: function() {
        var config = this.config;

        this.cardEntry = $(config.cardEntryQuery);
        this.termsCheckbox = $(config.termsCheckboxQuery);
        this.submitButton = $(config.submitButtonQuery);

        this.registerEvents();
    },

    /**
     * Bind the events
     */
    registerEvents: function() {
        this.form.bind('submit',
            $.proxy(this.validate, this)
        );
    },

    /*
     * Validate the form contents
     */
    validate: function() {
        var errors = [];

        if (!this.cardEntry.val()) {
            errors.push(FormMsg.fieldsMissing);
            this.toggleErrorFlag(this.cardEntry, true);
        } else {
            this.toggleErrorFlag(this.cardEntry, false);
        }

        if (this.termsCheckbox.length && !this.termsCheckbox[0].checked) {
            errors.push(FormMsg.tosDisagree);
            this.toggleErrorFlag(this.termsCheckbox, true);
        } else {
            this.toggleErrorFlag(this.termsCheckbox, false);
        }

        if (errors.length) {
            $('#content').prepend(FormHandler.makeErrorBox(errors));
            return false;
        } else {
            return true;
        }
    },

    /*
     * Add or remove error highlighting from form field
     *
     * @param [field]		The field on which to toggle error highlighting - expecting extended jQuery object
     * @param [hasError]	Boolean indicated whether to turn error highlighting on or off
     */
    toggleErrorFlag: function(field, hasError) {
        if (hasError) {
            field.parents('.form-row').addClass('form-error');
            field.parents('.terms-notice').addClass('form-error');
        } else {
            field.parents('.form-row').removeClass('form-error');
            field.parents('.terms-notice').removeClass('form-error');
        }
    }
};

function openCalculator(node) {
    var target = $('#calculate-result');
    if (target.is(':visible')) {
        target.hide();
        return;
    } else {
        $('#calculate-result').hide();
    }

    Toggle.open(node, '', '#calculate-result');
}

/**
 * get tax price for US
 *
 * @copyright   2011, Blizzard Entertainment, Inc.
 * @class       getTax
 * @requires	subtotal
 * @example
 *
 *      getTax.initialize(subtotal); // it will put in tax at <span class="taxinfo"/> and put in total price <span class="taxinfo-total"/>.
 */

var getTax = {

    productPrice: null,
    minimumPrice: null,
    totalPrice: null,
    totalPriceMinimum: null,
    accountBalance: null,
    accountBalancePage: null,
    accountBalancePageLoadCount: 0,
    accountBalanceError: null,
    accountBalanceErrorPage: null,
    accountBalanceErrorPageSub: null,
    accountBalancePartialError: null,
    remainTotalPrice: null,
    remainAddPrice: null,
    deficit : null,
    minBackupAmount : null,

    tax: null,
    taxClassElement: null,
    totalClassElement: null,
    totalSubmitElement: null,
    remainTotalElement: null,
    remainPaypalTotalElement: null,
    minimumPriceElement: null,
    remainAddBalanceElement: null,
    minimumElement: null,

    currency: null,

    firstname: null,
    lastname: null,
    address1: null,
    city: null,
    zipcode: null,
    phoneNumber1: null,

    flagPaymentMethod: null,
    flagAccountBalance: false,
    flagAccountBalancePartial: false,
    flagAccountBalancePage: 0,
    secondPaymentMethod: null,
    flagTaxLoad: 0,

    walletID: null,
    defaultBillingCountry: null,
    defaultBillingState: null,
    defaultBillingCity: null,
    defaultBillingZipcode: null,
    taxAddressCountry: null,
    taxAddressState: null,
    taxAddressCity: null,
    taxAddressZipcode: null,

    resourceURL: null,

    sufficientFunds: false,
    shippingAddressMap: {},

    initialize: function() {
        this.minimumElement = $(".minimumBalance");
        this.taxClassElement = $("#taxinfo-tax");
        this.totalClassElement = $(".taxinfo-total");
        this.totalSubmitElement = $("#totalSubmit");
        this.remainTotalElement = $("#accountBalanceRemainTotal");
        this.remainPaypalTotalElement = $("#paypalRemainTotal");
        this.minimumPriceElement = $("#minimumPrice");
        this.remainAddBalanceElement = $("#accountBalanceRemainAddBalance");
        this.remainBalanceElement = $("#accountBalanceDefault");

        if (Core.region != "us") {
            getTax.setTaxInfo(0);
            return false;
        }
        if(getTax.taxAddressCountry && getTax.flagTaxLoad == 0){
            getTax.getTaxInfoByAddress(getTax.taxAddressCountry,getTax.taxAddressState,getTax.taxAddressCity,getTax.taxAddressZipcode);
            getTax.flagTaxLoad++;
        }else{
            if(!getTax.taxAddressCountry){
                switch (getTax.flagPaymentMethod) {
                    case 'PayPal':
                        getTax.getTaxInfoByWallet(getTax.walletID);
                        break;
                    case 'CustomerWallet':
                        getTax.getTaxInfoByWallet(getTax.walletID);
                        break;
                    case 'otherPayment':
                        getTax.getTaxInfoByAddress(getTax.defaultBillingCountry,getTax.defaultBillingState,getTax.defaultBillingCity,getTax.defaultBillingZipcode);
                        break;
                    case 'accountBalance':
                        getTax.getTaxInfoByAddress(getTax.defaultBillingCountry,getTax.defaultBillingState,getTax.defaultBillingCity,getTax.defaultBillingZipcode);
                        break;
                    default :
                        getTax.getTaxInfoByAddress(getTax.defaultBillingCountry,getTax.defaultBillingState,getTax.defaultBillingCity,getTax.defaultBillingZipcode);
                }
            }
        }

        this.firstname = $(document.getElementById('billingAddress.firstname'));
        this.lastname = $(document.getElementById('billingAddress.lastname'));
        this.address1 = $(document.getElementById('billingAddress.address1'));
        this.city = $(document.getElementById('billingAddress.city'));
        this.zipcode = $(document.getElementById('billingAddress.zipcode'));
        this.phoneNumber1 = $(document.getElementById('billingAddress.phoneNumber1'));

        if (typeof(this.firstname[0]) != "undefined") {
            var fields = [ this.firstname[0], this.lastname[0], this.address1[0], this.city[0], this.zipcode[0], this.phoneNumber1[0] ];

            // Get event from Input fields
            $(fields).bind('keyup change', function() {
                if (getTax.flagPaymentMethod != 'accountBalance') {
                    getTax.checkRequired();
                }
            });
        }
    },

    /**
     * Check the required fields have been filled in.
     * If so, enable the form. Otherwise, disable it.
     */
    checkRequired: function() {
        var complete = true,
            firstname = this.firstname.val(),
            lastname = this.lastname.val(),
            address1 = this.address1.val(),
            city = this.city.val(),
            zipcode = this.zipcode.val(),
            phoneNumber1 = this.phoneNumber1.val();

        if (firstname.length < 2 || lastname === '' || address1.length < 2 || city.length < 2 || zipcode.length < 5 || phoneNumber1.length < 9) {
            complete = false;
        }

        if (complete) {
            getTax.getAddress();
        }
    },

    getTaxInfoByWallet: function(walletID) {
        var postData = { csrftoken: csrftoken, w: walletID };
        $.ajax({
            type: 'POST',
            timeout: 60000,
            url: Core.baseUrl + '/data/get-tax.html',
            data: postData,
            dataType: 'json',
            beforeSend: function() {
                $("#taxinfo-tax").text('').addClass("loading");
            },
            success: function(msg) {
                $("#taxinfo-tax").removeClass("loading");
                getTax.getSuccess(msg);
            },
            error: function() {
                getTax.setError();
            }
        });
    },

    getAddress: function() {
        var addressCountry = document.getElementById("billingAddress.country").value;
        if (addressCountry == "USA") {
            var addressState = document.getElementById('billingAddress.state').value,
                addressCity = document.getElementById('billingAddress.city').value,
                addressPostalcode = document.getElementById('billingAddress.zipcode').value;

            if (addressCity != '' && addressPostalcode != '') {
                this.getTaxInfoByAddress(addressCountry, addressState, addressCity, addressPostalcode);
            }
        }
    },

    getTaxInfoByShippingAddress: function(index){
        if(getTax.taxAddressCountry){
            getTax.getTaxInfoByAddress(getTax.taxAddressCountry,getTax.taxAddressState,getTax.taxAddressCity,getTax.taxAddressZipcode);
        }else{
            if (getTax.shippingAddressMap[index]){
                getTax.getTaxInfoByAddress(getTax.shippingAddressMap[index].country, getTax.shippingAddressMap[index].state, getTax.shippingAddressMap[index].city, getTax.shippingAddressMap[index].zipcode);
            }
        }
    },

    getTaxInfoByAddress: function(country, state, city, postalcode) {
        if (country !== null && country !== 'null') {
            var postData = {
                csrftoken: csrftoken,
                co: country,
                p: state,
                ci: city,
                pc: postalcode
            };
            $.ajax({
                type: 'POST',
                timeout: 60000,
                url: Core.baseUrl + '/data/get-tax.html',
                data: postData,
                dataType: 'json',
                beforeSend: function() {
                    $("#taxinfo-tax").text('').addClass("loading");
                },
                success: function(msg) {
                    $("#taxinfo-tax").removeClass("loading");
                    getTax.getSuccess(msg);
                    //reset address
                    getTax.taxAddressCountry = null;
                    getTax.defaultBillingCountry = null;
                },
                error: function() {
                    getTax.setError();
                }
            });
        }else{
            getTax.setTaxInfo(0);
        }
    },

    getSuccess: function(msg) {
        if (msg !== '' && msg.successful !== '') {
            if (msg.currency !== undefined) {
                if (typeof (msg.tax) == 'number') {
                    getTax.setTaxInfo(msg.tax);
                }else{
                    getTax.setTaxInfo(0);
                }
            }else{
                getTax.setError();
            }
        }
    },


    getSumPrice: function(a, b) {
        return a + b;
    },

    setTaxInfo: function(tax) {
        getTax.totalPrice = getTax.productPrice;

        if (Core.region == "us") {
            // value change to number
            getTax.accountBalance = accountBalance._getNumber(getTax.accountBalance);
            getTax.tax = accountBalance._getNumber(tax);
            getTax.productPrice = accountBalance._getNumber(getTax.productPrice);
            getTax.totalPrice = getTax.getSumPrice(getTax.tax, getTax.productPrice);

            // set tax to Element
            getTax.taxClassElement.text(accountBalance.getBalanceFormatting(getTax.tax, getTax.currency));
            // purchase-overview : price with tax
            getTax.totalClassElement.text(accountBalance.getBalanceFormatting(getTax.totalPrice, getTax.currency));
            // payment-toggle : price with tax
            getTax.totalSubmitElement.text(accountBalance.getBalanceFormatting(getTax.totalPrice, getTax.currency));
        }

        if (getTax.accountBalance > 0 && getTax.flagPaymentMethod == 'accountBalance') {
            getTax.setBalanceInfo(tax);
        }

        if (h.selectLevel === 2 && (getTax.secondPaymentMethod == 'CustomerWallet' || getTax.secondPaymentMethod === 'PaypalWalletEntry' || getTax.secondPaymentMethod === 'NewPayment' || getTax.secondPaymentMethod === 'PayPal') ) {
            getTax.setBalanceInfo(tax);
            getTax.accountBalancePageLoadCount=0;
        }else if (getTax.flagPaymentMethod == 'otherPayment' && ($("#secondary-payment-details").is(":visible"))) {
            var methodValue = $("#payment-types input:checked").val();
            h.selectLevel = 3;
            h.loadPayment(methodValue);
            getTax.accountBalancePageLoadCount++;
        }else if (getTax.flagPaymentMethod == 'accountBalance' && getTax.flagAccountBalance && getTax.sufficientFunds) {
            if (getTax.accountBalanceError !== true) {
                if (getTax.accountBalancePageLoadCount < 1) {
                    h.selectLevel = 1;
                    h.loadPayment(getTax.accountBalancePage);
                    getTax.accountBalancePageLoadCount++;
                }
            }
        }else{
            if(tax > 0){
                getTax.setBalanceInfo(tax);
            }
            getTax.accountBalancePageLoadCount=0;
            $("#payment-toggle").show();
        }
    },

    setBalanceInfo: function() {
        var formattedPrice;

        this.totalSubmitElement = $("#totalSubmit");
        this.remainPaypalTotalElement = $("#paypalRemainTotal");

        if (getTax.totalPrice > getTax.accountBalance && getTax.flagAccountBalancePartial) {
            $("#insufficient-note").show();

            if (getTax.secondPaymentMethod !== null && ($("#secondary-payment-details").is(":visible"))) {
                $("#selectLevel1").hide();
                $("#balance-info").show();
            }

            //Account balance
            getTax.remainBalanceElement.text(accountBalance.getBalanceFormatting(getTax.accountBalance, getTax.currency));

            //remain total
            getTax.remainTotalPrice = getTax.totalPrice - getTax.accountBalance;
            formattedPrice = accountBalance.getBalanceFormatting(getTax.remainTotalPrice, getTax.currency);
            getTax.remainTotalElement.text(formattedPrice);
            getTax.remainPaypalTotalElement.text(formattedPrice);

            //totalPrice with tax
            getTax.totalPrice = getTax.productPrice + getTax.tax;
            formattedPrice = accountBalance.getBalanceFormatting(getTax.totalPrice, getTax.currency);
            getTax.totalClassElement.text(formattedPrice);
            getTax.totalSubmitElement.text(formattedPrice);

            //remain price under minimum price
            if (getTax.deficit && getTax.minBackupAmount && getTax.deficit <= getTax.minBackupAmount) {
                if (getTax.tax <= getTax.minBackupAmount) {
                    if (getTax.flagAccountBalancePartial) {
                        getTax.minimumElement.addClass('display-bug');
                    }
                    getTax.minimumPrice = getTax.minBackupAmount;
                    formattedPrice = accountBalance.getBalanceFormatting(getTax.minimumPrice, getTax.currency);
                    getTax.minimumPriceElement.text(formattedPrice);
                    getTax.remainPaypalTotalElement.text(formattedPrice);

                    getTax.remainAddPrice = getTax.minimumPrice - getTax.deficit;
                    getTax.remainAddBalanceElement.text(accountBalance.getBalanceFormatting(getTax.remainAddPrice, getTax.currency));


                    getTax.totalPriceMinimum = getTax.accountBalance + getTax.minimumPrice;
                    formattedPrice = accountBalance.getBalanceFormatting(getTax.totalPriceMinimum, getTax.currency);
                    getTax.totalClassElement.text(formattedPrice);
                    getTax.totalSubmitElement.text(formattedPrice);

                }else{
                    getTax.minimumElement.hide();
                }
            }

            var paymentMethodValue = $("#payment-method").val();

            if (!getTax.flagAccountBalancePartial && paymentMethodValue && paymentMethodValue.indexOf("EBANK") != -1) {
                setTimeout('$("#payment-toggle").hide()', 200)
            }
        }else{
            getTax.remainBalanceElement.text(accountBalance.getBalanceFormatting(getTax.totalPrice, getTax.currency));
        }

        $(".partialClampsMap-" + getTax.flagAccountBalancePartial).show();
        $(".partialClampsMap-" + !getTax.flagAccountBalancePartial).hide();
    },

    accountBalancePageLoad: function(){
        if (getTax.accountBalancePageLoadCount < 1) {
            h.selectLevel = 1;
            h.loadPayment(getTax.accountBalancePage);
            getTax.accountBalancePageLoadCount++;
        }

    },

    setError: function() {
        //console.log("Error")
    }
};