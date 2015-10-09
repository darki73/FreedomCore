/**
 * Creates skinnable form elements from HTML5 form elements.
 *
 * @copyright   2011, Blizzard Entertainment, Inc
 * @class       Inputs
 * @requires    Core
 */
var Inputs = Class.extend({

    /**
     * jQuery objects for specific elements.
     */
    form: null,
    submitButton: null,
    requiredInputs: [],
    validationTimer: null,
    textInputs: [],
    textareaInputs: [],
    passwordInputs: [],
    checkboxInputs: [],
    checkboxLabels: [],
    checkboxAnchors: [],
    radioInputs: [],
    radioLabels: [],
    radioAnchors: [],
    radioSiblings: [],

    /**
     * Configuration. Used to selectively disable skinnable form elements.
     */
    config: {},

    /**
     *  Detect if the form exists, if so apply config and update inputs.
     *
     * @param	form Selector string for the parent <form> element, e.g., "#foobar"
     * @param	config
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

        if (this.form && this.form[0].tagName.toLowerCase() === 'form') {
            // Merge configuration.
            this.config = $.extend({
                checkbox: true, // skinnable checkboxes
                radio: true, // skinnable radio buttons
                placeholder: true, // HTML5 placeholder attribute
                novalidate: true, // HTML5 novalidate attribute (recommended since we do our own validation)
                required: true // HTML5 required attribute
            }, config);

            // Setup the class.
            this.setup();

            // Check to see if required fields have been filled in.
            this.validate();
        }

    },

    /**
     * Bind the event handlers, setup config, put on robe and wizard hat.
     */
    setup: function() {

        var config = this.config;

        this.submitButton = this.form.find('button[type=submit]');

        // Fancy checkboxInputs. NB: IE returns "FORM", so it gets regular inputs.
        if (config.checkbox && this.form[0].tagName === 'form') {
            this.checkboxInputs = this.form.find('input:checkbox');

            if (this.checkboxInputs.length) {
                this.setupCheckboxes();
            }
        }

        // Fancy radioInputs.
        if (config.radio && this.form[0].tagName.toLowerCase() === 'form') {
            this.radioInputs = this.form.find('input:radio');

            // NB: IE returns "FORM", so it gets regular inputs, but we still need this array for validation.
            if (this.radioInputs.length && this.form[0].tagName === 'form') {
                this.setupRadios();
            }
        }

        // JavaScript fallbacks for new HTML5 attributes.
        if (this.form[0].tagName.toLowerCase() === 'form') {
            this.textInputs = this.form.find('input[type="text"], input[type="email"], input[type="tel"]');
            this.textareaInputs = this.form.find('textarea');
            this.passwordInputs = this.form.find('input[type="password"]');
            this.requiredInputs = this.form.find('input[required], select[required], textarea[required]');

            if (this.textInputs.length && config.placeholder && !this.supportsPlaceholder()) {
                this.addPlaceholder(this.textInputs);
            }

            if (this.textareaInputs.length && config.placeholder && !this.supportsPlaceholder()) {
                this.addPlaceholder(this.textareaInputs);
            }

            if (this.passwordInputs.length && config.placeholder && !this.supportsPlaceholder()) {
                if (!this.isReadOnly()) {
                    this.addPlaceholder(this.passwordInputs, true);
                } else {
                    this.addPlaceholderMessage(this.passwordInputs);
                }
            }

            if (config.novalidate) {
                this.form.attr('novalidate', 'novalidate');
            }

            if (config.required) {
                this.addRequired(this.requiredInputs);
            }
        }

    },

    /**
     * Bind event listeners to required fields.
     */
    addRequired: function(fields) {

        // Form validation events.
        for (var i = 0, field, tag, type; field = fields[i]; i++) {

            // Reset the vars for each iteration.
            tag = $(field)[0].tagName.toLowerCase();
            type = '';

            // Check the user input.
            if (tag === 'select') {
                $(field).bind('change',
                    $.proxy(this._checkRequired, this)
                );
            } else if (tag === 'textarea') {
                $(field).bind('focus blur input propertychange',
                    $.proxy(this._checkRequired, this)
                );
            } else if (tag === 'input') {
                type = $(field)[0].type;
                if (type === 'checkbox' || type === 'radio') {
                    $(field).bind('change',
                        $.proxy(this._checkRequired, this)
                    );
                } else {
                    $(field).bind('focus blur input propertychange',
                        $.proxy(this._checkRequired, this)
                    );
                }
            }

        }

    },

    /**
     * Check the <iput required="required"/> elements for values.
     *
     * @param	e The event data.
     */
    _checkRequired: function(e) {
        var type = e.type,
            delay = (type === 'keyup' || type === 'input' || type === 'propertychange') ? 100 : 0,
            inputs = this;

        if (this.validationTimer === null) {
            this.validationTimer = setTimeout(function() {
                inputs.validate();
            }, delay);
            return true;
        }

        return false;
    },

    /**
     * Validate the form (presently only checks required fields for completion).
     * Triggers a custom event ('validate') when check is complete, along with the status of the check (true:false::pass:fail).
     */
    validate: function() {

        var form = this.form,
            complete = true,
            requiredInputs = this.requiredInputs,
            radioInputs = this.radioInputs;

        for (var i = 0, field, tag, type, value, name, placeholder; field = requiredInputs[i]; i++) {

            // Reset the vars for each iteration.
            tag = $(field)[0].tagName.toLowerCase();
            type = '';
            value = '';
            name = '';
            placeholder = '';

            // Check the user input.
            if (tag === 'select') {
                value = $(field).find('option:selected').val();
            } else if (tag === 'textarea') {
                value = $(field).val();
                placeholder = $(field).attr('placeholder');
            } else if (tag === 'input') {
                type = $(field)[0].type;
                if (type === 'checkbox') {
                    if ($(field)[0].checked) {
                        value = 'checked';
                    } else {
                        value = '';
                    }
                } else if (type === 'radio') {
                    if ($(field)[0].checked) {
                        value = 'checked';
                    } else {
                        name = $(field)[0].name;
                        value = '';
                        for (var j = 0, radio; radio = radioInputs[j]; j++) {
                            if (radio.name === name && radio.checked) {
                                value = 'checked';
                                break;
                            }
                        }
                    }
                } else {
                    value = $(field).val();
                    placeholder = $(field).attr('placeholder');
                }
            }

            // If something is missing, mark the form as incomplete.
            if (value === '' || value === placeholder) {
                complete = false;
            }

        }

        if (this.validationTimer !== null) {
            clearTimeout(this.validationTimer);
            this.validationTimer = null;
        }

        // A listener can be set up for this custom event in order to piggyback custom validation routines (e.g., email address validation).
        setTimeout(function() {
            form.trigger('validate', [complete]);
        }, 0);

        return complete;

    },

    /**
     * Apply new functionality to <input type="text"/> when not natively supported by the browser.
     *
     * @param	inputs An array of jQuery-extended <input/> elements.
     * @param	isPassword Indicates whether or not the <input/> is a password input.
     * 			Required since we flip type="" between "password" and "text" to display placeholder values.
     */
    addPlaceholder: function(inputs, isPassword) {

        isPassword = typeof isPassword === 'undefined' ? false : isPassword;

        for (var i = 0, text; text = inputs[i]; i++) {

            var placeholder;

            text = $(text);
            placeholder = text.attr('placeholder');

            if (typeof placeholder === 'undefined') {
                continue;
            }

            if (text.val() === '' || isPassword) {
                text.val(placeholder);
                text.addClass('placeholder');
                if (isPassword) {
                    text.attr('type', 'text');
                }
            }
            if (isPassword) {
                text.bind('focus blur',
                    $.proxy(this._togglePassword, this)
                );
            } else {
                text.bind('focus blur',
                    $.proxy(this._toggleText, this)
                );
            }

        }

    },

    /**
     * Display placeholder text underneath an input field for browsers that donвЂ™t support changing the input type (IE).
     *
     * @param	inputs An array of jQuery-extended <input/> elements.
     */
    addPlaceholderMessage: function(inputs) {

        for (var i = 0, text; text = inputs[i]; i++) {

            var placeholder,
                message;

            text = $(text);
            placeholder = text.attr('placeholder');
            message = $('#' + text[0].id + '-message');

            if (typeof placeholder === 'undefined') {
                continue;
            }

            if (text.val() === '' || isPassword) {
                message.text(placeholder);
            }

            text.bind('focus blur',
                $.proxy(this._toggleMessage, this)
            );

        }

    },

    /**
     * Toggle the placeholder="" with the value="" and vice versa for <input type="text"/>.
     *
     * @param	e The event data.
     */
    _toggleText: function(e) {
        var type = e.type,
            value = e.target.value,
            placeholder = $(e.target).attr('placeholder');
        if (type === 'blur' && value.length === 0) {
            e.target.value = placeholder;
            $(e.target).addClass('placeholder');
        } else if (type === 'focus') {
            if (value === placeholder) {
                e.target.value = '';
            }
            $(e.target).removeClass('placeholder');
        }
    },

    /**
     * Toggle the placeholder="" with the value="" and vice versa for <input type="text"/>.
     *
     * @param	e The event data.
     */
    _togglePassword: function(e) {
        var type = e.type,
            value = e.target.value,
            placeholder = $(e.target).attr('placeholder');
        if (type === 'blur' && value.length === 0) {
            e.target.value = placeholder;
            e.target.type = 'text';
            $(e.target).addClass('placeholder');
        } else if (type === 'focus') {
            if (value === placeholder) {
                e.target.value = '';
                e.target.type = 'password';
            }
            $(e.target).removeClass('placeholder');
        }
    },

    /**
     * Toggle the inline message for an <input/> element.
     *
     * @param	e The event data.
     */
    _toggleMessage: function(e) {
        var type = e.type,
            value = e.target.value,
            placeholder = $(e.target).attr('placeholder'),
            message = $('#' + e.target.id + '-message');;
        if (type === 'blur' && value.length === 0) {
            message.text(placeholder);
        } else if (type === 'focus') {
            if (value === placeholder) {
                message.text('');
            }
        }
    },

    /**
     * Hide each <input type="checkbox"/>, replace it with a styleable <a/> element, and bind events to its corresponding <label/>.
     */
    setupCheckboxes: function() {

        for (var i = 0, checkbox; checkbox = this.checkboxInputs[i]; i++) {

            var checked = checkbox.checked,
                id,
                href,
                rel,
                className = 'input-checkbox',
                tabindex,
                disabled,
                label,
                a;

            checkbox = $(checkbox);
            id = checkbox.attr('id');
            href = '#' + id;
            rel = checkbox.attr('name');
            className = checked ? className + ' input-checkbox-checked' : className;
            tabindex = checkbox.attr('tabindex');
            disabled = typeof checkbox.attr('disabled') !== 'undefined';
            if (checked) {
                className = disabled ? className + ' input-checkbox-checked-disabled' : className;
            } else {
                className = disabled ? className + ' input-checkbox-disabled' : className;
            }
            checkbox.before('<a id="' + id + '-link" href="' + href + '" rel="' + rel + '" class="' + className + '" tabindex="' + tabindex + '"></a>');
            checkbox.removeAttr('tabindex');
            checkbox.wrap('<span class="input-hidden" />');

            // Get the corresponding <label /> so we can bind events to it.
            this.checkboxLabels[i] = this.form.find('label[for="' + id + '"]');
            label = this.checkboxLabels[i];
            this.checkboxAnchors[i] = label.find('a[href="' + href + '"]');
            a = this.checkboxAnchors[i];

            if (disabled) {
                label.unbind().bind('mouseenter', function() {
                    $(this).css('cursor', 'default');
                }).bind('click', function() {
                    return false;
                });
            } else {
                label.unbind().bind('mouseenter', { checkbox: checkbox[0], a: a }, function(e) {
                    if (e.data.checkbox.checked) {
                        e.data.a.addClass('input-checkbox-checked-hover');
                    } else {
                        e.data.a.addClass('input-checkbox-hover');
                    }
                }).bind('mouseleave', { checkbox: checkbox[0], a: a }, function(e) {
                    if (e.data.checkbox.checked) {
                        e.data.a.removeClass('input-checkbox-checked-hover');
                    } else {
                        e.data.a.removeClass('input-checkbox-hover');
                    }
                }).bind('click', { checkbox: checkbox, checked: checked, a: a }, function(e) {
                    e.data.checkbox[0].checked = e.data.checkbox[0].checked ? false : true;
                    if (e.data.checkbox[0].checked) {
                        e.data.a.addClass('input-checkbox-checked');
                    } else {
                        e.data.a.removeClass('input-checkbox-checked');
                    }
                    e.data.a.removeClass('input-checkbox-checked-hover');
                    e.data.a.removeClass('input-checkbox-hover');
                    e.data.checkbox.trigger('change');
                    return (e.target.tagName === 'a' && !$(e.target).hasClass('input-checkbox'));
                }).bind('keyup', { checkbox: checkbox, checked: checked, a: a }, function(e) {
                    if (e.keyCode === 32) {
                        e.data.checkbox[0].checked = e.data.checkbox[0].checked ? false : true;
                        if (e.data.checkbox[0].checked) {
                            e.data.a.addClass('input-checkbox-checked');
                        } else {
                            e.data.a.removeClass('input-checkbox-checked');
                        }
                        e.data.a.removeClass('input-checkbox-checked-hover');
                        e.data.a.removeClass('input-checkbox-hover');
                        e.data.checkbox.trigger('change');
                        return (e.target.tagName === 'a' && !$(e.target).hasClass('input-checkbox'));
                    }
                }).bind('keydown', function(e) {
                    if (e.keyCode === 32) {
                        return false;
                    }
                });
            }

        }

    },

    /**
     * Toggle the placeholder="" with the value="" and vice versa for <input type="text"/>.
     *
     * @param	e The event data.
     */
    _toggleCheckbox: function(e) {
        var type = e.type,
            value = e.target.value,
            placeholder = $(e.target).attr('placeholder');
        if (type === 'blur' && value.length === 0) {
            e.target.value = placeholder;
            $(e.target).addClass('placeholder');
        } else if (type === 'focus') {
            if (value === placeholder) {
                e.target.value = '';
            }
            $(e.target).removeClass('placeholder');
        }
    },

    /**
     * Hide each <input type="radio"/>, replace it with a styleable <a/> element, and bind events to its corresponding <label/>.
     */
    setupRadios: function() {

        var i = 0,
            radio,
            checked,
            id,
            href,
            rel,
            className,
            tabindex,
            disabled,
            label,
            a;

        for (i = 0; radio = this.radioInputs[i]; i++) {

            checked = radio.checked;
            className = 'input-radio';
            radio = $(radio);
            id = radio.attr('id');
            href = '#' + id.replace('[', '').replace(']', '');
            rel = radio.attr('name');
            className = checked ? className + ' input-radio-checked' : className;
            tabindex = radio.attr('tabindex');
            disabled = typeof radio.attr('disabled') !== 'undefined';

            if (checked) {
                className = disabled ? className + ' input-radio-checked-disabled' : className;
            } else {
                className = disabled ? className + ' input-radio-disabled' : className;
            }
            radio.before('<a href="' + href + '" rel="' + rel + '" class="' + className + '" tabindex="' + tabindex + '"></a>');
            radio.removeAttr('tabindex');
            radio.wrap('<span class="input-hidden" />');

        }

        // Making a second loop for radios so we can find the siblings.
        for (i = 0; radio = this.radioInputs[i]; i++) {

            var s;
            checked = radio.checked;

            radio = $(radio);
            id = radio.attr('id');
            href = '#' + id.replace('[', '').replace(']', '');
            rel = radio.attr('name');
            className = checked ? className + ' input-radio-checked' : className;
            tabindex = radio.attr('tabindex');
            disabled = typeof radio.attr('disabled') !== 'undefined';

            // Get the corresponding <label /> so we can bind events to it.
            this.radioLabels[i] = this.form.find('label[for="' + id + '"]');
            label = this.radioLabels[i];
            this.radioAnchors[i] = label.find('a[href="' + href + '"]');
            a = this.radioAnchors[i];
            this.radioSiblings[i] = this.form.find('a[rel="' + rel + '"]');
            s = this.radioSiblings[i];

            if (disabled) {
                label.unbind().bind('mouseenter', function() {
                    $(this).css('cursor', 'default');
                }).bind('click', function() {
                    return false;
                });
            } else {
                label.unbind().bind('mouseenter', { radio: radio[0], a: a }, function(e) {
                    if (e.data.radio.checked) {
                        e.data.a.addClass('input-radio-checked-hover');
                    } else {
                        e.data.a.addClass('input-radio-hover');
                    }
                }).bind('mouseleave', { radio: radio[0], a: a }, function(e) {
                    if (e.data.radio.checked) {
                        e.data.a.removeClass('input-radio-checked-hover');
                    } else {
                        e.data.a.removeClass('input-radio-hover');
                    }
                }).bind('click', { radio: radio, checked: checked, a: a, s: s }, function(e) {
                    e.data.s.removeClass('input-radio-checked');
                    e.data.radio[0].checked = true;
                    if (e.data.radio[0].checked) {
                        e.data.a.addClass('input-radio-checked');
                    }
                    e.data.a.removeClass('input-radio-checked-hover');
                    e.data.a.removeClass('input-radio-hover');
                    e.data.radio.trigger('change');
                    return (e.target.tagName === 'a' && !$(e.target).hasClass('input-radio'));
                }).bind('keyup', { radio: radio, checked: checked, a: a, s: s }, function(e) {
                    if (e.keyCode === 32) {
                        e.data.s.removeClass('input-radio-checked');
                        e.data.radio[0].checked = true;
                        if (e.data.radio[0].checked) {
                            e.data.a.addClass('input-radio-checked');
                        }
                        e.data.a.removeClass('input-radio-checked-hover');
                        e.data.a.removeClass('input-radio-hover');
                        e.data.radio.trigger('change');
                        return (e.target.tagName === 'a' && !$(e.target).hasClass('input-radio'));
                    }
                }).bind('keydown', function(e) {
                    if (e.keyCode === 32) {
                        return false;
                    }
                });
            }

        }

    },

    isReadOnly: function() {
        return $.browser.msie;
    },

    supportsPlaceholder: function() {
        var i = document.createElement('input');
        return 'placeholder' in i;
    }

});