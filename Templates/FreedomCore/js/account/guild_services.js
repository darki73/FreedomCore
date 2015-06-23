$(document).ready(function() {
    GuildServices.initialize();

    if ($("#service-history").length) {
        var table = DataSet.factory('#service-history');
    }


    if ($("#guildmembers-table").length) {
        GuildServices.dataTable = DataSet.factory('#guildmembers-table', {
            paging: true,
            results: 8,
            pageCount: 6,
            totalResults: GuildServices.guildMemberGuid.length,
            cache: true
        });
    }

    if ($("#realm-table").length) {
        GuildServices.dataTableRealm = DataSet.factory('#realm-table', {
            paging: true,
            results: 8,
            pageCount: 6,
            totalResults: GuildServices.realmID.length,
            cache: true
        });
    }

});

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

var GuildServices = {
    typeAhead : null,
    dataTableRealm : null,
    dataTable : null,
    realmName : null,
    realmType : null,
    realmID : null,
    realmISP : null,
    realmLocale : null,
    guildMemberName : null,
    guildMemberGuid : null,
    newGuildNameCustomMessage : null,
    renameGuildCustomMessage : null,
    transferCustomMessage : null,
    characterGuildID : null,
    serviceUnavailableMsg : null,
    currentGuildName : null,
    refuseNameMsg : null,

    initialize: function() {

        $(".service-selector input").change(function() {
            if ($(this).is(':checked')) {
                GuildValidation.checkEligibility($(this).attr('id'));
            } else {
                GuildValidation.clearErrors($(this).attr('id'));
                GuildValidation.validateForm();
            }
            GuildServices.toggleService($(this));
        });

        GuildServices.bindListLinks();

        if ($('#realm-type-ahead').length) {
            GuildServices.initTypeAhead();
        }

        GuildServices.initGuildServiceMainForm();
    },

    initGuildServiceMainForm: function() {
        if ($("#PGT").is(':checked')) { $("#PGTLabel .chkbox").addClass("checked");}
        if ($("#PGFC").is(':checked')) { $("#PGFCLabel .chkbox").addClass("checked");}
        if ($("#PGNC").is(':checked')) { $("#PGNCLabel .chkbox").addClass("checked");}
        GuildServices.displayPrices();

        GuildServices.newGuildNameCustomMessage = $("#newGuildName").val();
        GuildServices.renameGuildCustomMessage = $("#stubGuildName").val();
        GuildServices.transferCustomMessage = $("#realm-type-ahead").val();

        if ($.browser.msie) {

        } else {
            if ($('#blade-name-change-open .service-submit').length != 0) {
                UI.freezeButton($('#blade-name-change-open .service-submit'));
            }
        }

        $("#newGuildName").bind('focus',function() {
            if (GuildServices.newGuildNameCustomMessage == $(this).val()) {
                $(this).val("");
            }
        }).bind("keypress", function(e) {
            if(e.which === 13) {
                e.preventDefault();
            }
        });
        $("#stubGuildName").bind('focus',function() {
            if (GuildServices.renameGuildCustomMessage == $(this).val()) {
                $(this).val("");
            }
        });
        $("#realm-type-ahead").bind('focus',function() {
            if (GuildServices.transferCustomMessage == $(this).val()) {
                $(this).val("");
            }
        });

        $("#newGuildName").bind('keyup change blur',function() {
            if ($(this).val().length >= 2) {
                if ($('#blade-name-change-open .service-submit').length != 0) {
                    UI.wakeButton($('#blade-name-change-open .service-submit'));
                }
                if ($('#blade-transfer-open .service-submit').length != 0) {
                    UI.wakeButton($('#blade-transfer-open .service-submit'));
                }
            } else {
                if ($('#blade-name-change-open .service-submit').length != 0) {
                    UI.freezeButton($('#blade-name-change-open .service-submit'));
                }
                if ($('#blade-transfer-open .service-submit').length != 0) {
                    UI.freezeButton($('#blade-transfer-open .service-submit'));
                }
            }
        });

        $("#stubGuildName").bind('keyup change blur',function() {
            if ($(this).val().length >= 2) {
                UI.wakeButton($('#blade-rename-open .service-submit'));
            } else {
                UI.freezeButton($('#blade-rename-open .service-submit'));
            }
        });

        $("#realm-type-ahead").bind('keyup change blur',function() {
            if ($(this).val().length == 0) {
                $("#targetRealmId").val('');
                if($('#blade-transfer-open .service-submit').length != 0) {
                    UI.freezeButton($('#blade-transfer-open .service-submit'));
                }
            } else {
                for (var i = 0, length = GuildServices.realmID.length; i < length; i++) {
                    if ($(this).val() === GuildServices.realmName[i]) {
                        $("#targetRealmId").val(GuildServices.realmID[i]);
                        if($('#blade-transfer-open .service-submit').length != 0) {
                            UI.wakeButton($('#blade-transfer-open .service-submit'));
                        }
                    }
                }
            }
        });
    },

    initTypeAhead: function() {

        var resultTypes = ['realm'];

        GuildServices.typeAhead = TypeAhead.factory('#realm-type-ahead', {
            minLength: 3,
            resultsLength: 10,
            termMatch: true,
            groupResults: true,
            selectKey: KeyCode.enter,
            resultTypes: resultTypes,
            ghostQuery: true,
            source: function(term, display) {
                var results = [];
                for (var i = 0, length = GuildServices.realmID.length; i < length; i++) {
                    if ((GuildServices.characterGuildID != null && GuildServices.realmID[i] != GuildServices.characterGuildID) || (GuildServices.characterGuildID == null)){
                        var data = {
                            type: 'realm',
                            title: GuildServices.realmName[i],
                            desc: GuildServices.realmType[i],
                            url: 'javascript:GuildServices.selectRealm(' + GuildServices.realmID[i] + ',' + i + ');'
                        };
                        results.push(data);
                    }
                }
                display(results);
            }
        });

    },

    validateMainForm: function() {
        var result = true

        if ($("#blade-name-change-complete").length != 0) {
            if (!$("#blade-name-change-complete").is(":visible")) { result = false; }
        }

        if ($("#blade-rename-complete").length != 0) {
            if (!$("#blade-rename-complete").is(":visible")) { result = false; }
        }

        if ($("#blade-gm-complete").length != 0) {
            if (!$("#blade-gm-complete").is(":visible")) { result = false; }
        }

        if ($("#blade-transfer-complete").length != 0) {
            if (!$("#blade-transfer-complete").is(":visible")) { result = false; }
        }

        if (result) {
            UI.wakeButton($('#submit1'));
        } else {
            UI.freezeButton($('#submit1'));
        }
    },

    editTransfer: function() {
        $("#blade-transfer-complete").hide();
        $("#blade-transfer-open").show();
    },

    editGM: function() {
        $("#blade-gm-complete").hide();
        $("#blade-gm-open").show();
    },

    editNameChange: function() {
        $("#blade-name-change-complete").hide();
        $("#blade-name-change-open").show();
    },

    editRename: function() {
        $("#blade-rename-complete").hide();
        $("#blade-rename-open").show();
    },

    selectRealm: function(realmID, i) {
        $("#targetRealmId").val(realmID);
        $("#realm-type-ahead").val(GuildServices.realmName[i]);
        $(".ui-typeahead").hide();
        $('#realmselect-dialog').dialog('close');
        if ($('#blade-transfer-open .service-submit').length != 0) {
            UI.wakeButton($('#blade-transfer-open .service-submit'));
        }
    },

    showRealmSelectDialog: function() {
        $('#realmselect-dialog').dialog('open');
        return false;
    },

    showGuildMemberDialog: function() {
        $('#guildmember-dialog').dialog('open');
        return false;
    },

    filterDataTable: function() {
        GuildServices.dataTable.reset();

        var name = $("#guildmember-dialog #name-filter").val();
        var lowLevel = parseInt($("#level-filter-low").val());
        var highLevel = parseInt($("#level-filter-high").val());

        if (!isNumber(highLevel)) { highLevel = 85; }
        if (!isNumber(lowLevel)) { lowLevel = 1; }

        if (lowLevel > highLevel) {
            var t = lowLevel;
            lowLevel = highLevel;
            highLevel = t;
        }

        $("#level-filter-low").val(lowLevel);
        $("#level-filter-high").val(highLevel);

        if (name != "") {
            GuildServices.dataTable.filter('column', '1', name, 'default');
        }
        GuildServices.dataTable.filter('column', '2', [lowLevel.toString(), highLevel.toString()], 'range');
    },

    filterRealmDataTable: function() {
        GuildServices.dataTableRealm.reset();

        var name = $("#realmselect-dialog #name-filter").val();
        var realmType = $("#realm-type-filter").val();
        var realmLocale= $("#realm-locale-filter").val();

        if (name != "") {
            GuildServices.dataTableRealm.filter('column', '4', name, 'default');
        }
        if (realmType != "") {
            GuildServices.dataTableRealm.filter('column', '2', realmType, 'default');
        }
        if (realmLocale != "") {
            GuildServices.dataTableRealm.filter('column', '3', realmLocale, 'default');
        }
    },

    resetFilter: function() {
        $("#level-filter-low").val('1');
        $("#level-filter-high").val('85');
        $("#name-filter").val('');

        GuildServices.dataTable.reset();

    },

    resetRealmFilter: function() {
        $("#realm-type-filter").val('');
        $("#realm-locale-filter").val('');
        $("#name-filter").val('');

        GuildServices.dataTableRealm.reset();

    },

    selectGuildMember: function(guid, name) {
        $('#guildmember-dialog').dialog('close');
        $('#selectedGuildMember').text(name);
        $('#stubGuildMasterGuid').val(guid);
        $("#blade-gm-open").hide();
        $("#blade-gm-complete .title .subtitle").text(": " + name)
        $("#blade-gm-complete").show();
        GuildServices.validateMainForm();
    },

    toggleService: function(element) {

        if (element.next().hasClass("checked")) {
            element.next().removeClass("checked");
        } else {
            element.next().addClass("checked");
        }

        GuildServices.displayPrices();

    },

    displayPrices: function() {
        var pgtChecked = $("#PGT").is(':checked');
        var pgfcChecked = $("#PGFC").is(':checked');
        var pgncChecked = $("#PGNC").is(':checked');

        if (!pgtChecked && !pgfcChecked && !pgncChecked) { // None selected
            GuildServices.hideAllPrices();
        } else if (pgtChecked && !pgfcChecked && !pgncChecked) { // PGT alone
            GuildServices.hideAllPrices();
            GuildServices.displayPriceExtras();
            $("#PGTPrice").show();
            $("#totalPrice").html($("#PGTPrice span").html());
        } else if (!pgtChecked && pgfcChecked && !pgncChecked) { // PGFC alone
            GuildServices.hideAllPrices();
            GuildServices.displayPriceExtras();
            $("#PGFCPrice").show();
            $("#totalPrice").html($("#PGFCPrice span").html());
        } else if (!pgtChecked && !pgfcChecked && pgncChecked) { // PGNC alone
            GuildServices.hideAllPrices();
            GuildServices.displayPriceExtras();
            $("#PGNCPrice").show();
            $("#totalPrice").html($("#PGNCPrice span").html());
        } else if (pgtChecked && pgfcChecked && !pgncChecked) { // PGT & PGFC
            GuildServices.hideAllPrices();
            GuildServices.displayPriceExtras();
            $("#PGFCPrice").show();
            $("#PGTPrice").show();
            $("#serviceDetailsExtra").show();
            $("#PGTPGFCDiscount").show();
            $("#PGNCIncluded").show();
            $("#totalPrice").html($("#PGCOMBOPrice span").html());
        } else if (pgtChecked && !pgfcChecked && pgncChecked) { // PGT & PGNC
            GuildServices.hideAllPrices();
            GuildServices.displayPriceExtras();
            $("#PGTPrice").show();
            $("#PGNCPrice").show();
            $("#serviceDetailsExtra").show();
            $("#PGTPGNCDiscount").show();
            $("#PGNCIncluded").show();
            $("#totalPrice").html($("#PGTPrice span").html());
        } else if (!pgtChecked && pgfcChecked && pgncChecked) { // PGFC & PGNC
            GuildServices.hideAllPrices();
            GuildServices.displayPriceExtras();
            $("#PGFCPrice").show();
            $("#PGNCPrice").show();
            $("#serviceDetailsExtra").show();
            $("#PGNCIncluded").show();
            $("#PGFCPGNCDiscount").show();
            $("#totalPrice").html($("#PGFCPrice span").html());
        } else if (pgtChecked && pgfcChecked && pgncChecked) { // ALL
            GuildServices.hideAllPrices();
            GuildServices.displayPriceExtras();
            //$("#PGCOMBOPrice").show();
            $("#PGTPrice").show();
            $("#PGFCPrice").show();
            $("#PGNCPrice").show();
            $("#serviceDetailsExtra").show();
            $("#PGNCIncluded").show();
            $("#ComboDiscount").show();
            $("#totalPrice").html($("#PGCOMBOPrice span").html());
        }

    },

    displayPriceExtras: function() {
        $(".service-caption").show();
        $("#serviceDetailsMain").show();
        $(".selected-service-total").show();
        $("#waitTime").show();
    },

    hideAllPrices: function() {
        $(".service-caption").hide();
        $("#serviceDetailsMain").hide();
        $("#PGTPrice").hide();
        $("#PGFCPrice").hide();
        $("#PGNCPrice").hide();
        $("#PGCOMBOPrice").hide();
        $("#serviceDetailsExtra").hide();
        $("#ComboDiscount").hide();
        $("#PGTPGFCDiscount").hide();
        $("#PGTPGNCDiscount").hide();
        $("#PGFCPGNCDiscount").hide();
        $(".selected-service-total").hide();
        $("#waitTime").hide();
    },

    bindListLinks: function() {
        $('.character-list ul li.character').unbind('click').bind({
            'click': function() {
                GuildServices.createCharacterLink(this);
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
                return GuildServices.toggleRealm();
            }
        });
        $('#realm-selector-link').bind({
            'click': function() {
                this.blur();
                return GuildServices.toggleRealm();
            }
        });
        $('#realm-selector-link-alt').bind({
            'click': function() {
                this.blur();
                return GuildServices.toggleRealm();
            }
        });
        $('#realm-selector-cancel').bind({
            'click': function() {
                this.blur();
                return GuildServices.toggleRealm();
            }
        });
    },

    toggleRealm: function() {

        var charList = "#" + $('#active-realm').attr('rel');

        if ($('#realm-selector').is(':visible')) {
            $('#realm-selector').hide();
            $('#realm-selector-link').show();
            $('#active-realm').removeClass('closed').addClass('opened');
            $(charList).show();
            return false;
        } else {
            $('#realm-selector').show();
            $('#realm-selector-link').hide();
            $('#active-realm').removeClass('opened').addClass('closed');
            $(charList).hide();
            if ($.browser.msie && $.browser.version < 7) { // IE6 is unable to position this element properly until its parent is :visible.
                setTimeout(function() { $('#realm-selector .form-row-stacked').css('clear', 'both'); }, 100);
            }
            return false;
        }

        return false;
    },

    createCharacterLink: function(target) {
        if ($(target).find('a').attr('href') !== undefined && $(target).find('a').attr('href') !== '') {
            var license = $(target).attr('id').split(':')[0],
                region = $(target).attr('id').split(':')[1],
                character = $(target).attr('id').split(':')[2];
            GuildServices._isCharacterEligible(target, license, region, character);
        }
    },

    _isCharacterEligible: function (target, license, region, character) {
        document.location.href = $(target).find('a').attr('href');
        return false;
    },

    toggleInstructions: function(target) {
        $(".container-" + target + " ." + target + " .describe").toggle();
        if ($(".container-" + target + " ." + target + " .more-info").hasClass("flipped")) {
            $(".container-" + target + " ." + target + " .more-info").removeClass("flipped");
        } else {
            $(".container-" + target + " ." + target + " .more-info").addClass("flipped");
        }
    }

};