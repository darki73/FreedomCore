var FreedomTagValidator = {
    form: null,
    totalResult:false,
    accountRegion: null,
    freedomTagInput: null,
    profanityAlert: null,
    buttonSubmit: null,
    buttonSkip: null,
    elementAlert: null,
    controlGroup: null,
    elementErrorAlert: null,
    elementLengthInfo: null,
    validateTimer: null,
    displayErrorTimer: null,
    displayErrorDelay: 250,

    charactersLength: 0,
    charactersLanguage: null,
    resultElement: [],

    patternNumber: null,
    patternLatinbasic: null,
    patternLatin:  null,
    patternKorean:  null,
    patternCyrillic:  null,
    patternChinese:  null,

    characterLength : {},

    initialize: function() {
        this.form = $("#freedomcoreIdForm");
        this.freedomTagInput = $("#freedomTag");
        this.controlGroup = this.freedomTagInput.parent().parent();
        if (!window.ScriptPackages) {
            this.buttonSubmit = $("#button-submit");
            this.buttonSkip = $("#skipBtag");
        }
        this.elementAlert = $("#freedomTag-Alert");
        this.elementErrorAlert = $("#error-alert");
        this.elementLengthInfo = $("#lengthInfo");

        for (var i=2; i < 6 ; i++) {
            FreedomTagValidator.resultElement[i] = $("#result" + i);
        }

        this.patternNumber = /^[\u0030-\u0039]$/;
        this.patternLatinbasic = /^[\u0041-\u005a\u0061-\u007a]$/;
        this.patternLatin = /^[\u0041-\u005a\u0061-\u007a\u00c0-\u00d6\u00d8-\u00f6\u00f8-\u017e\u0180-\u0188\u0190-\u0198\u01c0-\u0217]$/;
        this.patternKorean = /^[\uac00-\ud7af3]$/;
        this.patternCyrillic = /^[\u0400-\u04ff\u0500-\u052f]$/;
        this.patternChinese = /^[\u3400-\u4dbf\u4e00-\u9fff\uf900-\ufaff]$/;

        this.freedomTagInput.bind('input keydown change propertychange',
            $.proxy(this._validate, this)
        );

        if (window.ScriptPackages) {
            this.form.bind('battletag.submitForm',
                $.proxy(this.submitForm, this)
            );
            window.ScriptPackages.FreedomTag.callback = function() {
                $("#freedomcoreIdForm").trigger('battletag.submitForm');
            };
        } else {
            this.buttonSubmit.bind('click',
                $.proxy(this.submitForm, this)
            );
            this.buttonSkip.bind('click',
                $.proxy(this.submitFormSkip, this)
            );
        }
    },

    _validateAjax: function(e) {
        e.preventDefault();

        var characters = this.freedomTagInput.val(),
            result = new Array;
        result[1] = true;// result[1] 2-12 characters
        result[2] = true;// result[2] first character is not number
        result[3] = true;// result[3] freedomTag has wrong character
        result[4] = true;// result[4] freedomTag cannot convine other language
        result[5] = true;// result[5]

        if (characters.length > 2) {
            $.ajax({
                timeout: 60000,
                url: Core.baseUrl + '/data/battletag-validation.html?freedomTag=' + characters + '&_=' + new Date().getTime(),
                success: function(msg) {
                    if (msg !== '') {
                        if (!msg.valid) {
                            if (msg.LENGTH_TOO_LONG) {
                                result[1] = false;
                            }
                            if (msg.START_WITH_NUMBER) {
                                result[2] = false;
                            }
                            if (msg.INVALID_CHARACTER_USED) {
                                result[3] = false;
                                result[4] = false;
                            }
                            if (!msg.notReserved) {
                                result[5] = false;
                            }
                            if (!msg.notDuplicated) {
                                result[6] = false;
                            }
                        }
                        FreedomTagValidator.showError(result);
                    }
                }
            });
        }
    },

    _validate: function(e) {
        var type = e.type,
            delay = (type === 'keydown' || type === 'input' || type === 'propertychange') ? 100 : 0,
            bind = this;

        if (bind.validateTimer === null) {
            bind.validateTimer = setTimeout(function() {
                bind.showError(bind.validate(), type);
            }, delay);

            if (bind.triggerTimer !== null) {
                clearTimeout(this.triggerTimer);
                bind.triggerTimer = null;
            }
        }

        if(e.keyCode==13 && !this.totalResult){
            return false;
        }
    },

    _validateNumber: function(character) {
        return this.patternNumber.test(character);
    },

    _validateLatinbasic: function(character) {
        return this.patternLatinbasic.test(character);
    },

    _validateLatin: function(character) {
        return this.patternLatin.test(character);
    },

    _validateKorean: function(character) {
        return this.patternKorean.test(character);
    },

    _validateCyrillic: function(character) {
        return this.patternCyrillic.test(character);
    },

    _validateChinese: function(character) {
        return this.patternChinese.test(character);
    },

    _validateLanguage: function(language) {
        if (this.charactersLanguage == null || this.charactersLanguage == language) {
            this.charactersLanguage = language;
            return true;
        }else{
            return false;
        }
    },

    validate: function() {
        var characters,
            result = [],
            placeholder,
            i,
            character;

        characters = this.freedomTagInput.val();
        placeholder = this.freedomTagInput.attr('placeholder');

        result[0] = false;
        result[1] = true;// result[1] first character is not number
        result[2] = true;// result[2] freedomTag has wrong character
        result[3] = true;// result[3] freedomTag cannot have other language
        result[4] = true;// result[4] 2-12 characters

        if (characters !== placeholder) {
            if (!this._validateKorean(characters.charAt(0))) {
                if (/\s/g.test(characters)){
                    characters = characters.replace(/\s/g, '');
                    this.freedomTagInput.val(characters);
                }
            }
        }

        this.charactersLength = characters.length;
        if (this.charactersLength == 0 || this.charactersLanguage != null) {
            this.charactersLanguage = null;
        }

        for (i=0; i < this.charactersLength ; i++) {
            character = characters.charAt(i);

            if (!result[0]) {
                result[0] = this._validateNumber(character);
                if(result[0] && i == 0){
                    result[1] = !result[0];
                }
            }

            if (!result[0]) {
                if (this.accountRegion === 'EU' || this.accountRegion === 'NA' || this.accountRegion === 'SE' || this.accountRegion === 'PTR' || this.accountRegion === 'LA') {
                    result[0] = this._validateLatin(character);
                    if (result[0]) {
                        result[3] = this._validateLanguage('latin');
                    }
                }else{
                    result[0] = this._validateLatinbasic(character);

                    if (result[0]) {
                        result[3] = this._validateLanguage('latinbasic');
                    }
                }
            }

            if (!result[0] && (this.accountRegion === 'EU' || this.accountRegion === 'RU')) {
                result[0] = this._validateCyrillic(character);

                if (result[0]) {
                    result[3] = this._validateLanguage('cyrillic');
                }
            }

            if (!result[0] && this.accountRegion === 'KR') {
                result[0] = this._validateKorean(character);

                if (result[0]) {
                    result[3] = this._validateLanguage('korean');
                }
            }

            if (!result[0] && (this.accountRegion === 'CN' || this.accountRegion === 'TW') ) {
                result[0] = this._validateChinese(character);

                if (result[0]) {
                    result[3] = this._validateLanguage('chinese');
                }
            }

            if (result[0]) {
                result[0] = false;
            }else{
                if ((this.charactersLanguage == null && i == 0) || this.charactersLanguage != null) {
                    result[2] = false;
                }
            }

            if (!result[3]) {
                break;
            }
        }

        if (this.charactersLength >= 2 && this.charactersLanguage == null) {
            result[3] = false;
        }

        if (this.charactersLanguage != null) {
            result[4] = (this.charactersLength >= FreedomTagValidator.characterLength[this.charactersLanguage].min && this.charactersLength <= FreedomTagValidator.characterLength[this.charactersLanguage].max);
        }

        // display freedomTag length info
        this.toggleLengthInfo();

        if (this.validateTimer !== null) {
            clearTimeout(this.validateTimer);
            this.validateTimer = null;

            if (this.triggerTimer === null) {
                this.triggerTimer = setTimeout(function() {
                    FreedomTagValidator.showError(FreedomTagValidator.validate())
                }, 1000);
            }
        }

        return result;
    },

    submitForm: function() {
        if (window.ScriptPackages || !this.buttonSubmit.hasClass("disabled")){
            this.form[0].submit();
        }else{
            return false;
        }
    },

    submitFormSkip: function() {
        $("#skip")[0].value="true";
        this.form[0].submit();
    },

    /**
     * Top-level handler for displaying an error message.
     * For certain platforms, such as iOS, there needs to be a greater delay between inputting
     * values and showing the error message.
     *
     * @param result Error Results array from validate()
     */
    showError: function(result) {
        if (this.displayErrorTimer) {
            clearTimeout(this.displayErrorTimer);
        }
        var bind = this;
        this.displayErrorTimer = setTimeout(function() {
            bind._showError(result);
        }, this.displayErrorDelay);
    },

    _showError: function(result) {
        var i,
            resultLength = this.resultElement.length + 1,
            $element;

        this.totalResult = true;

        //reset alert and massage
        this.elementAlert.hide().find(".error-desc").hide();

        if(this.controlGroup.hasClass("control-error")){
            this.controlGroup.removeClass("control-error");
        }

        // display error message
        for (i=1; i < resultLength; i++) {
            if (result[i] === false) {
                // close top alert
                if (this.elementErrorAlert[0]){
                    this.elementErrorAlert.slideUp();
                }

                // display error message box
                this.elementAlert.show();

                if(!this.controlGroup.hasClass("control-error")){
                    this.controlGroup.addClass("control-error");
                    this.disable();
                }

                if(i == 4){
                    $element = $("#" + this.charactersLanguage);
                }else{
                    $element = this.resultElement[i + 1];
                }

                $element.show();
                this.fadeOutError($element, this.elementAlert);

                this.totalResult = false;

                break;
            }
        }

        // control submit button
        if(!this.controlGroup.hasClass("control-error")){
            this.enable();
            if(this.charactersLength == 0){
                this.disable();
                this.elementLengthInfo.text("")
            }
        }


    },

    /**
     * fadeOut error message for phoenix
     *
     * @param {jQuery DOM node} $element      Specific error message container
     * @param {jQuery DOM node} $container    Error messages container
     */
    fadeOutError: function($element, $container) {
        window.ScriptPackages && setTimeout(function() {
            $element.fadeOut(1000);
            setTimeout(function() {
                $container.hide();
            }, 1000);
        }, 2000);
        //$element.css("display", "block").addClass("fade-out");
    },

    /**
     * toggle for length info color
     */
    toggleLengthInfo: function() {
        if(this.elementLengthInfo[0] && this.charactersLanguage){
            this.freedomTagInput.attr("maxlength", this.characterLength[this.charactersLanguage].max);

            if((this.charactersLength >= this.characterLength[this.charactersLanguage].min && this.charactersLength <= this.characterLength[this.charactersLanguage].max)){
                if(this.elementLengthInfo.hasClass("text-error")){
                    this.elementLengthInfo.removeClass("text-error");
                }
                if(!this.elementLengthInfo.hasClass("text-success")){
                    this.elementLengthInfo.addClass("text-success");
                }
            }else{
                if(!this.elementLengthInfo.hasClass("text-error")){
                    this.elementLengthInfo.addClass("text-error");
                }
                if(this.elementLengthInfo.hasClass("text-success")){
                    this.elementLengthInfo.removeClass("text-success");
                }
            }
            this.elementLengthInfo.text(this.charactersLength + "/" + this.characterLength[this.charactersLanguage].max);
        }else{
            //if user doesn't enter any language, it will be disappear(number and special character)
            this.elementLengthInfo.text('');
        }
    },

    /**
     * Enable the submit button.
     */
    enable: function() {
        this.form.off('submit');
        if (window.ScriptPackages) {
            window.ScriptPackages.FreedomTag.enable();
        } else {
            this.buttonSubmit.removeClass('disabled').removeAttr('disabled');
        }
    },

    /**
     * Prevent the form from being submitted.
     */
    disable: function() {
        this.form.on('submit', function() {
            return false;
        });
        if (window.ScriptPackages) {
            window.ScriptPackages.FreedomTag.disable();
        } else {
            this.buttonSubmit.addClass('disabled').attr('disabled', 'disabled');
        }
    }
};

$(document).ready(function() {
    FreedomTagValidator.initialize();
});

