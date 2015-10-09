$(document).ready(function() {
    AddGame.initialize();
});

/**
 * Add game form.
 *
 * @copyright   2010, Blizzard Entertainment, Inc.
 * @class       AddGame
 * @example
 *
 *      AddGame.initialize();
 *
 */
var AddGame = {
    form: '',
    requiredField: {},
    submitButton: {},
    initialize: function() {
        AddGame.form = '#add-game';
        AddGame.requiredField = $(AddGame.form + ' .required input');
        AddGame.submitButton = $('#add-game-submit');

        if (!AddGame.form.length) {
            return false;
        }

        AddGame.requiredField.bind({
            'keyup': function() {
                if ($(this).val().length >= 1) {
                    setTimeout(function() {
                        UI.wakeButton(AddGame.submitButton);
                    }, 250);
                } else {
                    UI.freezeButton(AddGame.submitButton);
                }
            },
            'blur': function() {
                if ($(this).val().length >= 1) {
                    UI.wakeButton(AddGame.submitButton);
                } else {
                    UI.freezeButton(AddGame.submitButton);
                }
            }
        });

        var urlVar = document.URL, getWord = "key=";
        urlVar = (urlVar.split("?")[1] ? urlVar.split("?")[1] : "").split("&");

        for (var i = 0, len = urlVar.length; i < len; i++) {
            if(urlVar[i].indexOf(getWord) > -1){
                var keyWordVar = urlVar[i].split(getWord)[1];
                $("#gameKey").val(keyWordVar);
                UI.wakeButton(AddGame.submitButton);
            }
        }

    }
};

var FormAnchor = {
    initialize : function() {
        $(".form-anchor").bind({
            'click' : function() {
                var f = $(this).attr('href'),
                    parents = $(".form-anchor").parent(),
                    parent = null,
                    child = null,
                    icon = null,
                    subForm = null,
                    length = 0,
                    i = 0,
                    collapsed = false;

                for (i = 0, length = parents.length; i < length; i++) {
                    parent = $(parents[i]);
                    child = parent.children('.form-anchor');
                    child = parent.children('.form-anchor');
                    if (child.attr('href') === f && parent.hasClass('open')) {
                        collapsed = true;
                        continue;
                    }
                }

                for (i = 0, length = parents.length; i < length; i++) {
                    parent = $(parents[i]);
                    child = parent.children('.form-anchor');
                    icon = child.children("span.icon-32");
                    subForm = parent.children('.sub-form');
                    if (child.attr('href') === f && !parent.hasClass('open')) {
                        parent.addClass('open');
                        child.removeClass('faded');
                        icon.addClass('open').removeClass('closed-faded');
                        subForm.show();
                    } else {
                        parent.removeClass('open');
                        if (collapsed) {
                            child.removeClass('faded');
                            icon.removeClass('open').removeClass('closed-faded');
                        } else {
                            child.addClass('faded');
                            icon.removeClass('open').addClass('closed-faded');
                        }
                        subForm.hide();
                    }
                }

                return false;
            }
        });
    }
};