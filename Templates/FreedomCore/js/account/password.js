/**
 * Password validation and strength rating.
 *
 * @copyright   2011, Blizzard Entertainment, Inc
 * @class       Password
 */
var Password = Class.extend({

    /**
     * Regex patterns for password validation.
     * passwordPattern1: The ASCII printable character set
     * passwordPattern2: At least one letter and one number
     * maxRepetition: Use lower values to permit greater repetition.
     * maxSequentiality: Use lower values to permit greater sequentiality.
     */
    passwordPattern1: new RegExp(),
    passwordPattern2: new RegExp(),
    maxRepetition: 0,
    maxSequentiality: 0,
    maxSimilarity: 4,

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
            passwordPattern1: new RegExp('^[\x20-\x7E]+$'),
            passwordPattern2: new RegExp('^(?=.*[0-9]+.*)(?=.*[a-zA-Z]+.*).+$'),
            maxRepetition: 2,
            maxSequentiality: 4,
            maxSimilarity: 4
        }, config);

        config = this.config;

        this.passwordPattern1 = config.passwordPattern1;
        this.passwordPattern2 = config.passwordPattern2;
        this.maxRepetition = config.maxRepetition;
        this.maxSequentiality = config.maxSequentiality;
        this.maxSimilarity = config.maxSimilarity;

    },

    /**
     * Check password to ensure it meets minimum requirements.
     * Returns 0 if condition was not checked or password was empty.
     * Returns -1 if condition was not met.
     * Returns 1 if condition was met.
     *
     * @param password1 The password to be checked.
     * @param password2 The re-entered password.
     * @param email The userвЂ™s email address.
     */
    check: function(password1, password2, email) {

        password1 = typeof password1 !== 'undefined' ? password1 : '';
        password2 = typeof password2 !== 'undefined' ? password2 : '';
        email = typeof email !== 'undefined' ? email : '';

        var results = [0, 0, 0, 0, 0];

        if (password1.length > 0) {
            // Password must be between 8вЂ“16 characters in length.
            if (password1.length >= 8 && password1.length <= 16) {
                results[0] = 1;
            } else {
                results[0] = -1;
            }
            // Password may only contain ASCII printable characters.
            if (this.passwordPattern1.test(password1)) {
                results[1] = 1;
            } else {
                results[1] = -1;
            }
            // No spaces allowed at beginning or end of password
            if (password1.charAt(0) == ' ' || password1.charAt(password1.length-1) == ' ') {
                results[1] = -1;
            }
            // Password must contain at least one alphabetic character and one numeric character.
            if (this.passwordPattern2.test(password1)) {
                results[2] = 1;
            } else {
                results[2] = -1;
            }
            // Cannot use password similar to account name.
            results[3] = this.isSimilar(password1, email);
            // Passwords must match.
            if (password2 === '') {
                results[4] = 0;
            } else if (password1 === password2) {
                results[4] = 1;
            } else {
                results[4] = -1;
            }
        }

        return results;
    },

    /**
     * Rate a passwordвЂ™s strength.
     * Returns 0 if password is empty or untested.
     * Returns 1 if password is too short.
     * Returns 2 if password is weak.
     * Returns 3 if password is fair.
     * Returns 4 if password is strong.
     *
     * @param password1 The password to be rated.
     */
    rate: function(password1) {

        password1 = typeof password1 !== 'undefined' ? password1 : '';

        var result = 0;

        if (password1.length > 0) {
            if (password1.length >= 8) {
                if (this.passwordPattern1.test(password1) && this.passwordPattern2.test(password1) && password1.length > 10 && !this.hasRepetition(password1) && !this.hasSequentiality(password1)) {
                    result = 4;
                } else {
                    if (this.passwordPattern1.test(password1) && this.passwordPattern2.test(password1) && password1.length > 8 && !this.hasRepetition(password1) && !this.hasSequentiality(password1)) {
                        result = 3;
                    } else {
                        result = 2;
                    }
                }
            } else {
                result = 1;
            }
        }

        return result;

    },

    /**
     * Check for repetition in a string.
     * Returns true if the string has high repetition, false otherwise.
     *
     * @param string The string to check.
     */
    hasRepetition: function(string) {

        string = typeof string !== 'undefined' ? string : '';
        pLen = typeof pLen !== 'undefined' ? pLen : 2;

        var pLen = this.maxRepetition,
            res = '',
            repeated;

        for (var i = 0, len = string.length; i < len; i++) {
            repeated = true;
            for (var j = 0; j < pLen && (j + i + pLen) < string.length; j++) {
                repeated = repeated && (string.charAt(j + i) === string.charAt(j + i + pLen));
            }
            if (j < pLen) {
                repeated = false;
            }
            if (repeated) {
                i += pLen - 1;
                repeated = false;
            } else {
                res += string.charAt(i);
            }
        }

        return res.length - string.length < 0;

    },

    /**
     * Check for sequentiality in a string.
     * Returns true if the string has high sequentiality, false otherwise.
     *
     * @param string The string to check.
     */
    hasSequentiality: function(string) {

        string = typeof string !== 'undefined' ? string.toLowerCase() : '';

        var pLen = this.maxSequentiality,
            lowercase = 'abcdefghijklmnopqrstuvwxyz',
            lowercaseReverse = lowercase.split('').reverse().join(''),
            numbers = '1234567890',
            numbersReverse = numbers.split('').reverse().join(''),
            qwerty = 'qwertyuiopasdfghjklzxcvbnm!@#$%^&*()_+',
            qwertyReverse = qwerty.split('').reverse().join(''),
            chunk = ' ' + string.slice(0, pLen - 1);

        for (var i = pLen, len = string.length; i < len; i++) {
            chunk = chunk.slice(1) + string.charAt(i);
            if (lowercase.indexOf(chunk) > -1 || numbers.indexOf(chunk) > -1 || qwerty.indexOf(chunk) > -1 ||
                lowercaseReverse.indexOf(chunk) > -1 || numbersReverse.indexOf(chunk) > -1 || qwertyReverse.indexOf(chunk) > -1) {
                return true;
            }
        }

        return false;

    },

    /**
     * Check if password is similar to account name.
     * Returns -1 if similar.
     * Returns 1 if not.
     *
     * @param password1 The password to be checked.
     * @param email The userвЂ™s email address.
     */
    isSimilar: function(password1, email) {
        if (password1 === "" || email === "" || password1.length < this.maxSimilarity) {
            return 1;
        }
        password1 = password1.toLowerCase();
        email = email.toLowerCase();
        for (var i = 0; i <= email.length - this.maxSimilarity; i++) {
            if (password1.indexOf(email.substring(i, i + this.maxSimilarity)) > -1) {
                return -1;
            }
        }
        return 1;
    }

});

/**
 * Change Battle.net Account Password.
 *
 * @copyright   2011, Blizzard Entertainment, Inc
 * @class       ChangePassword
 */
var ChangePassword = Class.extend({

    /**
     * jQuery objects for specific elements.
     */
    form: null,

    passwordInput1: null,
    passwordMessage1: null,
    passwordMessage1Default: null,
    passwordInput2: null,
    passwordMessage2: null,
    passwordMessage2Default: null,
    passwordRight: null,
    passwordLeft: null,
    passwordResult: null,
    passwordRating: null,
    passwordLevels: [],
    passwordGuidelines: null,
    passwordValidator: new Password(),
    passwordValidated: false,
    passwordTimer: null,
    showPasswordGuidelines: false,
    activePasswordInput: -1,

    emailAddress: '',

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
                passwordFields: [
                    '#password',
                    '#rePassword'
                ],
                emailAddress: ''
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

        this.passwordInput1 = $(this.config.passwordFields[0]);
        this.passwordMessage1 = $(this.config.passwordFields[0] + '-message');
        this.passwordMessage1Default = this.passwordMessage1.text();
        this.passwordInput2 = $(this.config.passwordFields[1]);
        this.passwordMessage2 = $(this.config.passwordFields[1] + '-message');
        this.passwordMessage2Default = this.passwordMessage2.text();
        this.passwordRight = this.passwordInput1.parents('span.input-right');
        this.passwordLeft = this.passwordRight.siblings('span.input-left');
        this.passwordResult = $('#password-result');
        this.passwordRating = $('#password-rating');
        this.passwordGuidelines = $('#password-strength');

        this.emailAddress = this.config.emailAddress;

        for (var i = 0; i < 5; i++) {
            this.passwordLevels[i] = $('#password-level-' + i);
        }

        // Bind the form submit event.
        this.form.bind('submit', { form: this }, function(e) {
            var flaggedBlank = false, validEntry = true, errorMsgs = [];
            for (var i = 0, len = this.length; i < len; i++) {
                var thisField = $(this[i]);
                if (thisField.attr('required') && !thisField.val()) {
                    if (!flaggedBlank) {
                        errorMsgs.push(FormMsg.fieldsMissing);
                        flaggedBlank = true;
                    }
                    validEntry = false;
                }
            }
            if (validEntry) {
                if (!e.data.form.passwordValidated) {
                    e.data.form.checkPassword();
                    if (!e.data.form.passwordValidated) {
                        errorMsgs.push(FormMsg.passwordError1);
                        validEntry = false;
                    }
                }
            }
            if (!validEntry) {
                $('#content').prepend(e.data.form.makeErrorBox(errorMsgs));
                window.location.href = "#form-errors";
            }
            UI.enableButton(e.data.form.submitButton);
            return validEntry;
        });

        // Dynamic password strength rating.
        this.passwordInput1.bind('focus blur input',
            $.proxy(this._validatePassword, this)
        );

        if(Core.isIE) {
            this.passwordInput1.bind('keyup',
                $.proxy(this._validatePassword, this)
            );
        }

        this.passwordInput2.bind('focus blur input',
            $.proxy(this._validatePassword, this)
        );

    },

    /**
     * Internal call to checkPassword() that uses a timer as a limiter.
     *
     * @param e The event data.
     */
    _validatePassword: function(e) {
        var type = e.type,
            target = e.target.id,
            password1 = this.passwordInput1[0].id,
            delay = (type === 'keyup' || type === 'input' || type === 'propertychange') ? 100 : 0,
            bind = this;
        this.showPasswordGuidelines = false;
        this.activePasswordInput = -1;
        if (type !== 'blur') {
            this.activePasswordInput = target === password1 ? 0 : 1;
            this.showPasswordGuidelines = target === password1;
        }
        if (this.passwordTimer === null) {
            this.passwordTimer = setTimeout(function() {
                bind.checkPassword();
            }, delay);
            return true;
        }
        return false;
    },

    /**
     * Check password to ensure it meets minimum strength requirements.
     */
    checkPassword: function() {
        var pass = this.passwordValidator,
            result = true,
            password1 = this.passwordInput1[0].type === 'password' ? this.passwordInput1[0].value : '',
            password2 = this.passwordInput2[0].type === 'password' ? this.passwordInput2[0].value : '',
            rowRight = this.passwordRight,
            rowLeft = this.passwordLeft,
            message1 = this.passwordMessage1,
            message2 = this.passwordMessage2,
            default1 = this.passwordMessage1Default,
            default2 = this.passwordMessage2Default,
            email = this.emailAddress,
            show = this.showPasswordGuidelines;

        if (password1.length > 0) {
            rowRight.removeClass('input-error');
            rowLeft.removeClass('input-error');

            var checkResult = pass.check(password1, password2, email);
            for (var i = 0, level, pw; pw = checkResult[i]; i++) {
                level = this.passwordLevels[i];
                level.removeClass();
                if (pw === 1) {
                    level.addClass('pass');
                } else if (pw === -1) {
                    rowRight.addClass('input-error');
                    rowLeft.addClass('input-error');
                    level.addClass('fail');
                    result = false;
                }
            }

            if (result || show) {
                message1.html('В ');
            } else if (!result || message2.html() === default2 || message2.html() === 'В ') {
                message1.html(FormMsg.passwordError1);
            }

        } else {
            message1.html(default1);
            message2.html(default2);
            this.passwordLevels[0].removeClass();
            this.passwordLevels[1].removeClass();
            this.passwordLevels[2].removeClass();
            this.passwordLevels[3].removeClass();
            this.passwordLevels[4].removeClass();
            result = false;
        }

        this.ratePassword();

        this.passwordValidated = result;

        return result;
    },

    /**
     * Rate a passwordвЂ™s strength.
     */
    ratePassword: function() {
        var pass = this.passwordValidator,
            score = 0,
            password1 = this.passwordInput1[0].type === 'password' ? this.passwordInput1[0].value : '',
            rating = this.passwordRating,
            result = this.passwordResult,
            message1 = this.passwordMessage1,
            message2 = this.passwordMessage2;

        rating.removeClass().addClass('rating rating-default');
        result.html('').removeClass();

        if (password1.length > 0) {
            score = pass.rate(password1);
            if (score === 4) {
                rating.removeClass().addClass('rating rating-strong');
                result.html(FormMsg.passwordStrength3);
            } else if (score === 3) {
                rating.removeClass().addClass('rating rating-fair');
                result.html(FormMsg.passwordStrength2);
            } else if (score === 2) {
                rating.removeClass().addClass('rating rating-weak');
                result.html(FormMsg.passwordStrength1);
            } else if (score === 1) {
                rating.removeClass().addClass('rating rating-short');
                result.html(FormMsg.passwordStrength0).addClass('fail');
            }
        }

        if (this.showPasswordGuidelines) {
            message1.html('В ');
            message2.html('В ');
            var arrow = this.passwordGuidelines.find('div.input-note-arrow');
            this.passwordGuidelines.slideDown(250);
        } else {
            this.passwordGuidelines.slideUp(125);
        }

        if (this.passwordTimer !== null) {
            clearTimeout(this.passwordTimer);
            this.passwordTimer = null;
        }

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
            + '<p class="title"><strong><a name="form-errors">' + FormMsg[((errorCount>1)?"headerMultiple":"headerSingular")] + '</a></strong></p>';
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