/**
 * Email validation.
 *
 * @copyright   2011, Blizzard Entertainment, Inc
 * @class       Email
 */
var Email = Class.extend({

    /**
     * Regex patterns for password validation.
     */
    emailPattern: new RegExp(),

    /**
     * Configuration.
     */
    config: {},

    /**
     * Initialize the class and apply the config.
     */
    init: function(config) {

        config = typeof config !== 'undefined' ? config : {};

        // Merge configuration
        this.config = $.extend({
            emailPattern: new RegExp('^[0-9a-zA-Z+_.-]+@[0-9a-zA-Z_-]+\\.[0-9a-zA-Z_.-]+$')
        }, config);

        this.emailPattern = this.config.emailPattern;

    },

    /**
     * Check email address to ensure it meets minimum requirements.
     * Returns 0 if condition was not checked or email was empty.
     * Returns -1 if condition was not met.
     * Returns 1 if condition was met.
     *
     * @param email1 The email address to be checked.
     * @param email2 The re-entered email address (only checked if config.verify is true).
     */
    check: function(email1, email2) {

        email1 = typeof email1 !== 'undefined' ? email1 : '';
        email2 = typeof email2 !== 'undefined' ? email2 : '';

        var results = [0, 0];

        if (email1 === '') {
            results[0] = 0;
        } else if (this.emailPattern.test(email1)) {
            results[0] = 1;
        } else {
            results[0] = -1;
        }

        if (email1 === '') {
            results[1] = 0;
        } else if (email1 === email2) {
            results[1] = 1;
        } else {
            results[1] = -1;
        }

        return results;

    }

});

/**
 * Change Battle.net Account E-mail Address.
 *
 * @copyright   2011, Blizzard Entertainment, Inc
 * @class       ChangeEmail
 */
var ChangeEmail = Class.extend({

    /**
     * jQuery objects for specific elements.
     */
    form: null,

    emailInput1: null,
    emailMessage1: null,
    emailInput2: null,
    emailMessage2: null,
    emailRight: null,
    emailLeft: null,
    emailValidator: new Email(),
    emailValidated: false,
    emailTimer: null,
    activeEmailInput: -1,
    autocompleteOptions: {},

    /**
     * Configuration.
     */
    config: {},

    /**
     * Initialize the class and apply the config.
     *
     * @param form The account creation form (e.g., '#change-settings').
     * @param config An optional config object.
     */
    init: function(form, config) {

        config = typeof config !== 'undefined' ? config : {};
        form = $(form);

        if (form.length) {
            if (form[0].tagName.toLowerCase() !== 'form') {
                form = form.find('form');
            }

            this.form = (form.length) ? form : null;
        }

        if (this.form !== null) {
            // Merge configuration
            this.config = $.extend({
                emailFields: [
                    '#emailAddress',
                    '#emailAddressConfirmation'
                ],
                domains: []
            }, config);
            // Setup the class
            this.setup();
        }

    },

    /**
     * Find the elements, bind the event handlers, animalВ print pants out of control.
     */
    setup: function() {

        var form = this.form;

        this.submitButton = form.find('button[type=submit]');

        this.emailInput1 = $(this.config.emailFields[0]);
        this.emailMessage1 = $(this.config.emailFields[0] + '-message');
        this.emailInput2 = $(this.config.emailFields[1]);
        this.emailMessage2 = $(this.config.emailFields[1] + '-message');
        this.emailRight = this.emailInput1.parents('span.input-right');
        this.emailLeft = this.emailRight.siblings('span.input-left');

        // Bind the form submit event.
        this.form.bind('submit', { form: this }, function(e) {
            var validEntry = true, flaggedBlank = false, errorMsgs = [];
            if (!e.data.form.emailValidated) {
                e.data.form.validateEmail();
                if (!e.data.form.emailValidated) {
                    errorMsgs.push( $('#newEmail').val() == $('#newEmailVerify').val() ? FormMsg.emailError1 : FormMsg.emailError2 );
                    validEntry = false;
                }
            }
            //if (validEntry) {
            for (var i = 0, len = this.length; i < len; i++) {
                var thisField = $(this[i]);
                if (thisField.attr('required') && !thisField.val()) {
                    if (!flaggedBlank) {
                        errorMsgs.push(FormMsg.fieldsMissing);
                        flaggedBlank = true;
                    }
                    thisField.parents('span.input-right').addClass('input-error');
                    thisField.parents('span.input-right').siblings('span.input-left').addClass('input-error');
                    validEntry = false;
                } else {
                    thisField.parents('span.input-right').removeClass('input-error');
                    thisField.parents('span.input-right').siblings('span.input-left').removeClass('input-error');
                }
            }
            //}
            UI.enableButton(e.data.form.submitButton);
            if (!validEntry) {
                $('#content').prepend(e.data.form.makeErrorBox(errorMsgs));
            }
            return validEntry;
        });

        // Domain autocompletion for email addresses
        if (this.config.domains.length) {
            this.autocompleteOptions = {
                minChars: 0,
                matchSubset: false,
                matchContains: true,
                source: []
            };
            this.emailInput1.autocomplete(this.autocompleteOptions);
            this.emailInput2.autocomplete(this.autocompleteOptions);
        }

        // Inline messaging for e-mail addresses.
        this.emailInput1.bind('focus blur',
            $.proxy(this._validateEmail, this)
        ).bind('input propertychange',
            $.proxy(this._completeEmail, this)
        );

        this.emailInput2.bind('focus blur',
            $.proxy(this._validateEmail, this)
        ).bind('input propertychange',
            $.proxy(this._completeEmail, this)
        );

    },

    /**
     * Autocomplete the domain name for an e-mail address.
     *
     * @param e The event data.
     */
    _completeEmail: function(e) {
        var domains = this.config.domains,
            target = e.target.id,
            email1 = this.emailInput1[0].id;
        if (domains.length) {
            var atTest = new RegExp('^[0-9a-zA-Z+_.-]+@+$');

            if (atTest.test(e.target.value)) {
                var userName = e.target.value,
                    emailIDs = [];
                for (var i = 0, domain; domain = domains[i]; i++) {
                    emailIDs.push(userName + domain);
                }
                this.autocompleteOptions.source = emailIDs;
                this.emailInput1.autocomplete(this.autocompleteOptions);
                this.emailInput2.autocomplete(this.autocompleteOptions);
            } else {
                this.autocompleteOptions.source = [];
            }
        }
        if (target !== email1) {
            this._validateEmail(e);
        }
    },

    /**
     * Internal call to validateEmail() that uses a timer as a limiter.
     *
     * @param e The event data.
     */
    _validateEmail: function(e) {


        var type = e.type,
            target = e.target.id,
            email1 = this.emailInput1[0].id,
            email2 = this.emailInput2[0].id,
            length1 = this.emailInput1[0].value.length,
            length2 = this.emailInput2[0].value.length,
            delay = (type === 'keyup' || type === 'input' || type === 'propertychange') ? 100 : 0,
            bind = this;

        if (target === email1 && type === 'focus') {
            clearTimeout(this.emailTimer);
            this.emailTimer = null;
            this.emailMessage1.html(FormMsg.emailInfo);
        } else if (target === email2 && type !== 'blur' && length1 > length2) {
            this.emailMessage2.html('В ');
        } else if (this.emailTimer === null) {
            this.emailTimer = setTimeout(function() {
                bind.validateEmail();
            }, delay);
        }
        if(target === email1 && type === 'blur' && $.browser.msie && $.browser.version.substr(0,1) < 7){
            $("#question1").show();
        }
    },

    /**
     * Validate an e-mail address.
     */
    validateEmail: function() {
        var pass = true,
            email1 = this.emailInput1[0].value,
            email2 = this.emailInput2[0].value,
            rowRight = this.emailRight,
            rowLeft = this.emailLeft,
            placeholder1 = this.emailInput1.attr('placeholder'),
            placeholder2 = this.emailInput2.attr('placeholder'),
            validator = this.emailValidator,
            results;

        // Make sure weвЂ™re not validating placeholder text.
        if (email1 === placeholder1) {
            email1 = '';
        }
        if (email2 === placeholder2) {
            email2 = '';
        }

        results = validator.check(email1, email2);

        // Inline error for field #1.
        if (results[0] === -1) {
            this.emailMessage1.html(FormMsg.emailError1);
            pass = false;
        } else {
            this.emailMessage1.html('В ');
        }

        // Inline error for field #2.
        if (email2 !== '' && results[1] === -1) {
            this.emailMessage2.html(FormMsg.emailError2);
            pass = false;
        } else {
            this.emailMessage2.html('В ');
        }

        if (this.emailTimer !== null) {
            clearTimeout(this.emailTimer);
            this.emailTimer = null;
        }

        if (pass) {
            if (this.activeEmailInput > -1) {
                rowRight.removeClass('input-error');
                rowLeft.removeClass('input-error');
            }
            this.activeEmailInput = 1;
        } else {
            rowRight.addClass('input-error');
            rowLeft.addClass('input-error');
        }

        this.emailValidated = pass;
        return pass;
    },

    /**
     * Enable the submit button.
     */
    enable: function() {
        this.submitButton.removeClass('disabled').removeAttr('disabled');
    },

    /**
     * Prevent the form from being submitted.
     */
    disable: function() {
        this.submitButton.addClass('disabled').attr('disabled', 'disabled');
    },

    makeErrorBox: function(errorMsgs) {
        $('#content .alert').remove();
        var errorCount = errorMsgs.length;
        var errorHtml = ''
            + '<div class="alert error closeable border-4 glow-shadow">'
            + '<div class="alert-inner">'
            + '<div class="alert-message">'
            + '<p class="title"><strong><a name="form-errors"> </a>' + FormMsg[((errorCount > 1) ? 'headerMultiple' : 'headerSingular')] + '</strong></p>';
        if (errorCount>1) {
            errorHtml += '<ul>';
            for (var i=0;i<errorCount;i++) {
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

});