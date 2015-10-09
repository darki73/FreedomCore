

$(document).ready(function() {
    var table;

    if ($("#service-history").length) {
        table = DataSet.factory('#service-history');
    }

    if ($("#raf-history").length) {
        table = DataSet.factory('#raf-history');
    }

    if ($("#sor-history").length) {
        table =  DataSet.factory('#sor-history');
    }

    Services.bindFormCancel();
    Services.bindListLinks();
    Services.focusTargetRealm();
    Services.checkRealmExists();
    Services.initForm();
    Services.localizeTime();
    Services.bindInlineRadio();

    $("#customMessage").focus(
        function() {
            this.select();
        }
    );

    if ($('#realm-type-ahead').length) {
        Services.initTypeAhead();
    }

});

var Services = {
    dataTableRealm : null,
    typeAhead : null,
    realmName : null,
    realmType : null,
    realmID : null,
    realmISP : null,
    realmLocale : null,
    licenseCount : 0,
    selectedRealm : 0,
    currentMoveType : null,
    accountMoveType : null,
    timer: null,
    sourceRealmISP : 'NONE',
    accountRegion : null,
    noLicense : null,

    initTypeAhead: function() {

        var resultTypes = ['realm'];
        Services.typeAhead = TypeAhead.factory('#realm-type-ahead', {
            minLength: 3,
            resultsLength: 10,
            termMatch: true,
            groupResults: true,
            selectKey: KeyCode.enter,
            resultTypes: resultTypes,
            ghostQuery: true,
            useSearchList: true,
            source: function(term, display) {
                var results = [];
                for (var i = 0, length = Services.realmID.length; i < length; i++) {
                    var data = {
                        type: 'realm',
                        title: Services.realmName[i],
                        desc: Services.realmType[i],
                        searchTerm: Services.realmSearchName[i],
                        url: 'javascript:Services.selectRealm(' + Services.realmID[i] + ',' + i + ');'
                    };
                    results.push(data);
                }
                display(results);
            }
        });

        $("#realm-type-ahead").bind('keyup change blur',function() {
            var thisVal = $(this).val(),
                targetRealmId = $("#targetRealmId");
            if (thisVal.length != 0) {
                for (var i = 0, length = Services.realmID.length; i < length; i++) {
                    if (thisVal === Services.realmName[i]) {
                        targetRealmId.val(Services.realmID[i]);
                        Services.matchSelectByVal(Services.realmID[i]);
                        break;
                    }else{
                        targetRealmId.val("");
                        $('#isp-warning').hide();
                    }
                }
            }else{
                targetRealmId.val("");
            }
        });

    },
    selectRealm: function(realmID, i) {
        $("#targetRealmId").val(realmID).click();
        $("#realm-type-ahead").val(Services.realmName[i]);
        $(".ui-typeahead").hide();
        $('#realmselect-dialog').dialog('close');
        $(".input-ghost").val('')
    },

    showRealmSelectDialog: function() {
        $('#realmselect-dialog').dialog('open');
        return false;
    },
    filterRealmDataTable: function() {
        Services.dataTableRealm.reset();

        var name = $("#name-filter").val();
        var realmType = $("#realm-type-filter").val();
        var realmLocale= $("#realm-locale-filter").val();

        if (name != "") {
            Services.dataTableRealm.filter('column', '1', name, 'default');
        }
        if (realmType != "") {
            Services.dataTableRealm.filter('column', '2', realmType, 'default');
        }
        if (realmLocale != "") {
            Services.dataTableRealm.filter('column', '3', realmLocale, 'default');
        }
    },

    resetRealmFilter: function() {
        $("#realm-type-filter").val('');
        $("#realm-locale-filter").val('');
        $("#name-filter").val('');

        Services.dataTableRealm.reset();

    },
    bindInlineRadio : function() {
        $(".input-radio-inline label").bind({
            'click': function() {
                var gmFollowChecked = $("#gmFollow").is(':checked');
                var gmNotFollowChecked = $("#gmNotFollow").is(':checked');

                if (gmFollowChecked) {
                    $("#gmFollowLabel .input-radio").addClass("input-radio-checked");
                    $("#gmNotFollowLabel .input-radio").removeClass("input-radio-checked");
                    $("#go").attr("href",$("#gmFollow").attr("rel"));
                } else {
                    $("#gmFollowLabel .input-radio").removeClass("input-radio-checked");
                    $("#gmNotFollowLabel .input-radio").addClass("input-radio-checked");
                    $("#go").attr("href",$("#gmNotFollow").attr("rel"));
                }

                $("#go").removeClass("disabled").removeAttr("disabled");

            }
        });
    },

    updateSoRRealmID : function() {
        var realmId = $("#sr").val();
        if (realmId != 0) { $("#realmId").val(realmId); } else { $("#realmId").val() }
    },

    bindFormCancel : function() {
        $(".submit-cancel").click( function(){
            $(this).parents("form").submit();
        });
    },

    bindListLinks: function() {
        $('.character-list ul li.character').unbind('click').bind({
            'click': function() {
                Services.createCharacterLink(this);
                return false;
            }
        });
        $('.character-list ul li.clu').unbind('click').bind({
            'click': function() {
                Services.createCharacterLink(this);
                return false;
            }
        });
        $('.character-list ul li.error a.character-link').unbind('click').bind({
            'click': function() {
                return false;
            }
        });
        $('.character-list ul li.pending a.character-link').unbind('click').bind({
            'click': function() {
                return false;
            }
        });
        $('#active-realm').bind({
            'click': function() {
                this.blur();
                return Services.toggleRealm();
            }
        });
        $('#realm-selector-link').bind({
            'click': function() {
                this.blur();
                return Services.toggleRealm();
            }
        });
        $('#realm-selector-link-alt').bind({
            'click': function() {
                this.blur();
                return Services.toggleRealm();
            }
        });
        $('#realm-selector-cancel').bind({
            'click': function() {
                this.blur();
                return Services.toggleRealm();
            }
        });
        $('input[type=radio]').bind({
            'click': function() {
                if (this.id.indexOf('transferOptions') !== -1) {
                    Services.showTransferOptions(this.value);
                }
                if (this.id.indexOf('movingType') !== -1) {
                    Services.showAccountOptions(this.value);
                }

            }
        });
    },

    toggleRealm: function() {

        var charList = "#" + $('#active-realm').attr('rel');

        if ($('#realm-selector').is(':visible')) {
            (!Core.isIE(6)) ? $('#realm-selector').slideUp() : $('#realm-selector').hide();
            $('#realm-selector-link').show();
            $('#active-realm').removeClass('closed').addClass('opened');
            (!Core.isIE(6)) ? $(charList).slideDown() : $(charList).show();

        } else {
            (!Core.isIE(6)) ? $('#realm-selector').slideDown() : $('#realm-selector').show();
            $('#realm-selector-link').hide();
            $('#active-realm').removeClass('opened').addClass('closed');
            (!Core.isIE(6)) ? $(charList).slideUp() : $(charList).hide();
        }

        return false;
    },

    createCharacterLink: function(target) {
        if ($(target).find('a').attr('href') !== undefined && $(target).find('a').attr('href') !== '') {
            var license = $(target).attr('id').split(':')[0],
                region = $(target).attr('id').split(':')[1],
                character = $(target).attr('id').split(':')[2];
            Services._isCharacterEligible(target, license, region, character);
        }
    },

    _isCharacterEligible: function (target, license, region, character) {
        var serviceName = additionalMessages.active.serviceName,
            viewingRealm = additionalMessages.active.viewingRealm,
            status = '',
            reasons = '',
            i = 0,
            length = 0;

        if (serviceName == "FCM") {
            viewingRealm = $(target).attr('id').split(':')[3];
        }

        if (serviceName == "SOR_SENDER" || serviceName == "SOR_CLU" || serviceName == "SOR_CS") {
            document.location.href = $(target).find('a').attr('href');
            return false;
        }

        $.ajax({
            url: Core.baseUrl + '/account/management/services/is-character-eligible',
            data: ({
                service: serviceName,
                accountName: license,
                guid: region,
                character: character,
                sr: viewingRealm,
                salt: new Date().getTime()
            }),
            beforeSend: function() {
                clearTimeout(self.timer);
                status = '<div class="character-status"><span class="status-loading caption">' + additionalMessages.loading.title + '</span></div>';
                if ($(target).find('div.character-status')) {
                    $(target).find('div.character-status').hide();
                }
                $(target).removeClass('character').addClass('loading').append(status);
                $('.character-list ul li.character').unbind('click');
            },
            success: function(msg) {
                self.timer = setTimeout(function() {
                    $(target).find('.character-status').remove();
                    $(target).removeClass('loading');
                    if (serviceName == "RAF") {
                        document.location.href = $(target).find('a').attr('href');
                        return false;
                    } else if (msg.reasons === undefined) {
                        $('.character-list ul li.character').unbind('click').bind({
                            'click': function() {
                                Services.createCharacterLink(this);
                                return false;
                            }
                        });
                        status = '<div class="character-status"><span class="status-error caption"><strong>' + additionalMessages.error.title + '</strong> ' + additionalMessages.error.serverTitle + '</span><div class="status-options">' + additionalMessages.error.serverDesc + '</div></div>';
                        $(target).addClass('error').append(status);
                        return false;
                    } else if (msg.eligible === true) {
                        document.location.href = $(target).find('a').attr('href');
                        return false;
                    } else {
                        $('.character-list ul li.character').unbind('click').bind({
                            'click': function() {
                                Services.createCharacterLink(this);
                                return false;
                            }
                        });
                        length = msg.reasons.length;

                        status = '<div class="character-status"><span class="status-error caption"><strong>' + additionalMessages.error.title.substr(0,10) + '</strong> ';
                        if (additionalMessages.error[msg.reasons[0] + 'Title'] === undefined)
                            status += msg.reasons[0];
                        else
                            status += additionalMessages.error[msg.reasons[0] + 'Title'];
                        status += '</span><div class="status-options">' + additionalMessages.error.multiDesc + '<br />' + '</div></div>';
                        reasons = '<div id="char-' + character + '">';
                        reasons += '<ul>';
                        for (var i=0; i < length; i++) {
                            reasons += '<li><h3>';
                            if (additionalMessages.error[msg.reasons[i] + 'Title'] === undefined)
                                reasons += msg.reasons[i];
                            else
                                reasons += additionalMessages.error[msg.reasons[i] + 'Title'];
                            reasons += '</h3>';
                            if (additionalMessages.error[msg.reasons[i] + 'Desc'] !== undefined)
                                reasons += additionalMessages.error[msg.reasons[i] + 'Desc'];
                            reasons += '</li>';
                        }
                        reasons += '</ul>';
                        reasons += '</div>';
                        status = status.replace('#error-container', '#char-' + character);
                        $('#error-container').append(reasons);

                        $(target).addClass('error').append(status);
                        return false;
                    }
                }, 500);
            },
            error: function() {
                self.timer = setTimeout(function() {
                    $('.character-list ul li.character').unbind('click').bind({
                        'click': function() {
                            Services.createCharacterLink(this);
                            return false;
                        }
                    });
                    status = '<div class="character-status"><span class="status-error caption"><strong>' + additionalMessages.error.title + '</strong> ' + additionalMessages.error.serverTitle + '</span><div class="status-options">' + additionalMessages.error.serverDesc + '</div></div>';
                    $(target).find('.character-status').remove();
                    $(target).removeClass('loading').addClass('error').append(status);
                    return false;
                }, 500);
            }
        });
    },

    initForm: function() {
        if (Services.licenseCount == 0) {
            $('#movingType-0').attr('disabled', 'true'); $('label[for="movingType-0"]').css('color', '#999');
            $('#movingType-0').parent().next().attr("data-tooltip", Services.noLicense).attr("data-tooltip-options", '{"location": "mouse"}');
        }


        Services.matchSelectByVal(Services.selectedRealm);
        Services.showTransferOptions(Services.currentMoveType);

        if (Services.currentMoveType === 'movingRealms') {
            $('#transferOptions-0').attr('checked', 'checked');
        } else if (Services.currentMoveType === 'movingAccounts') {
            $('#transferOptions-1').attr('checked', 'checked');
        } else if (Services.currentMoveType === 'movingBoth') {
            $('#transferOptions-2').attr('checked', 'checked');
        }

        $('#targetRealmId option[selected=true]').removeAttr('selected');

        if (Services.realmID) {
            for (var i = 0, length = Services.realmID.length; i < length; i++) {
                if (Services.realmID[i] == Services.selectedRealm) {
                    $('#targetRealmId option[value="' + Services.realmID[i] + '"]').attr('selected', true);
                    $('#combobox-targetRealmId').attr('value', Services.realmName[i] + ' - ' + Services.realmType[i]);
                }
            }
        }

        if (Services.accountMoveType === 'LICENSE') {
            $('#movingType-0').attr('checked', 'checked');
        } else if (Services.accountMoveType === 'ACCOUNT') {
            $('#movingType-1').attr('checked', 'checked');
        }
        Services.showAccountOptions(Services.accountMoveType);

        if (Services.accountRegion == "KO" || Services.accountRegion == "KR") {
            $("#transferOptions-1").attr("disabled", "disabled")
                .parent().next().find("strong").addClass("label-disabled");
            $("#transferOptions-2").attr("disabled", "disabled")
                .parent().next().find("strong").addClass("label-disabled");
        }

    },

    showTransferOptions: function(val) {

        if (val === 'movingRealms') {
            Services.showSubForm('movingRealms-SubForm');
            Services.hideSubForm('movingAccounts-SubForm');
            Services.hideSubForm('secretQuestion-SubForm');
            $('#hiddenMovingRealms').attr('checked', 'checked');
            $('#hiddenTransferringAccounts').removeAttr('checked');
            $('#changingFactionContainer').show();
        } else if (val === 'movingAccounts') {
            Services.hideSubForm('movingRealms-SubForm');
            Services.showSubForm('movingAccounts-SubForm');
            Services.showSubForm('secretQuestion-SubForm');
            $('#hiddenMovingRealms').removeAttr('checked');
            $('#hiddenTransferringAccounts').attr('checked', 'checked');
            $('#changingFactions').each(function() {this.checked = false;});
            $('#changingFactionContainer').hide();
        } else if (val === 'movingBoth') {
            Services.showSubForm('movingRealms-SubForm');
            Services.showSubForm('movingAccounts-SubForm');
            Services.showSubForm('secretQuestion-SubForm');
            $('#hiddenMovingRealms').attr('checked', 'checked');
            $('#hiddenTransferringAccounts').attr('checked', 'checked');
            $('#changingFactions').each(function() {this.checked = false;});
            $('#changingFactionContainer').hide();
        }

    },

    showAccountOptions: function(val) {

        if (val === 'LICENSE') {
            Services.showSubForm('movingTypeLICENSE-SubForm');
            Services.hideSubForm('movingTypeACCOUNT-SubForm');
            Services.hideSubForm('secretQuestion-SubForm');
        } else if (val === 'ACCOUNT') {
            Services.hideSubForm('movingTypeLICENSE-SubForm');
            Services.showSubForm('movingTypeACCOUNT-SubForm');
        } else {
            Services.hideSubForm('movingTypeLICENSE-SubForm');
            Services.hideSubForm('movingTypeACCOUNT-SubForm');
        }
    },

    showSubForm: function(formID) {
        var target = $('#' + formID);

        if (!target.is(':visible')) {
            (!Core.isIE(6)) ? target.slideDown() : target.show();
        }
    },

    hideSubForm: function(formID) {
        var target = $('#' + formID);

        // Clean up previously filled data when user switch category
        var textFields = target.find('input[type=text]');
        var selectFields = target.find('select');

        if(textFields.length) {
            textFields.val(function() {
                return $(this).hasClass('combobox') ? '\u2026' : '';
            });
        }
        if(selectFields.length) {
            selectFields.val('');
        }

        (!Core.isIE(6)) ? target.slideUp() : target.hide();
    },

    toggleSubForm: function(formID) {
        var target = $('#' + formID);

        if (target.is(':visible')) {
            (!Core.isIE(6)) ? target.slideUp() : target.hide();
        } else {
            (!Core.isIE(6)) ? target.slideDown() : target.show();
        }
    },

    checkRealmExists: function() {
        $('#targetRealmId').click(function() {
            $('#submit').removeAttr('disabled').removeClass('disabled');
            var val = $(this).val();
            Services.matchSelectByVal(val);
        });
        $('#combobox-targetRealmId').change(function() {
            var text = $(this).val();
            text = text.substring(0, text.indexOf('-') - 1);
            for (var i = 0, length = Services.realmName.length; i < length; i++) {
                if (text === Services.realmName[i]) {
                    $('#submit').removeAttr('disabled').removeClass('disabled');
                    Services.matchSelect(text);
                }
            }
        });

    },

    matchSelect: function(text) {
        $('#destinationRealmSelect option[selected=true]').removeAttr('selected');

        if (Services.realmID) {
            for (var i = 0, length = Services.realmName.length; i < length; i++) {
                if (Services.realmName[i] == text) {
                    $('#destinationRealmSelect option[value="' + Services.realmID[i] + '"]').attr('selected', true);
                    if (Services.sourceRealmISP !== 'NONE') {
                        if (Services.sourceRealmISP !== Services.realmISP[i]) {
                            $('#isp-warning').show();
                        } else {
                            $('#isp-warning').hide();
                        }
                    }
                }
            }
        }
    },

    matchSelectByVal: function(val) {
        $('#destinationRealmSelect option[selected=true]').removeAttr('selected');
        if (Services.realmID) {
            for (var i = 0, length = Services.realmID.length; i < length; i++) {
                if (Services.realmID[i] == val) {
                    $('#destinationRealmSelect option[value="' + Services.realmID[i] + '"]').attr('selected', true);
                    if (Services.sourceRealmISP !== 'NONE') {
                        if (Services.sourceRealmISP !== Services.realmISP[i]) {
                            $('#isp-warning').show();
                        } else {
                            $('#isp-warning').hide();
                        }
                    }
                }
            }
        }
    },

    focusTargetRealm: function() {
        $('#combobox-targetRealmId').focus(function() { this.select(); });
    },

    localizeTime: function() {
        var timeHolders = $('.time-localization');
        if (!timeHolders.length) {
            return false;
        }
        var format = 'dd-MM-yyyy HH:mm';
        switch (Core.locale) {
            default:
            case 'cs-cz':
            case 'de-de':
            case 'pl-pl':
                format = 'dd.MM.yyyy HH:mm';
                break;
            case 'en-us':
                format = 'MM/dd/yyyy hh:mm a';
                break;
            case 'en-gb':
            case 'es-es':
            case 'es-mx':
            case 'fr-fr':
            case 'pt-br':
            case 'it-it':
            case 'ru-ru':
                format = 'dd/MM/yyyy HH:mm';
                break;
            case 'en-sg':
                format = 'dd/MM/yyyy hh:mm a';
                break;
            case 'ja-ja':
            case 'ko-kr':
                format = 'yyyy/MM/dd HH:mm';
                break;
            case 'zh-cn':
                format = 'yyyyе№ґMMжњ€ddж—ҐHH:mm';
                break;
            case 'zh-tw':
                format = 'yyyy-MM-dd HH:mm';
                break;
        }
        timeHolders.each(function() {
            var el = $(this);
            var formated = Core.formatDatetime(format, el.text());
            el.text(formated ? formated : el.text());
        });
    }

};