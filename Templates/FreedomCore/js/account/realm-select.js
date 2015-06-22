//////////////////////////////////////////////////////////////////////////////////////////////////
//  This js file is a WIP.  The purpose of this file is to strip realm selection out of character
//  services functions and make it a stand alone module.
//////////////////////////////////////////////////////////////////////////////////////////////////

var RealmSelector = {
    dataTableRealm: null,
    typeAhead: null,
    realmName: null,
    realmSearchName: null,
    realmType: null,
    realmID: null,
    realmISP: null,
    realmLocale: null,
    init: function() {
        if ($('#realm-type-ahead').length) {
            RealmSelector.initTypeAhead();
        }
        if ($("#realm-table").length) {
            RealmSelector.dataTableRealm = DataSet.factory('#realm-table', {
                paging: true,
                results: 8,
                pageCount: 6,
                totalResults: RealmSelector.realmID.length,
                cache: true
            });
        }
    },
    initTypeAhead: function() {
        var resultTypes = ['realm'];
        RealmSelector.typeAhead = TypeAhead.factory('#realm-type-ahead', {
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
                for (var i = 0, length = RealmSelector.realmID.length; i < length; i++) {
                    var data = {
                        type: 'realm',
                        title: RealmSelector.realmName[i],
                        desc: RealmSelector.realmType[i],
                        searchTerm: RealmSelector.realmSearchName[i],
                        url: 'javascript:RealmSelector.selectRealm(' + RealmSelector.realmID[i] + ',' + i + ');'
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
                for (var i = 0, length = RealmSelector.realmID.length; i < length; i++) {
                    if (thisVal === RealmSelector.realmName[i] || thisVal === RealmSelector.realmSearchName[i]) {
                        targetRealmId.val(RealmSelector.realmID[i]);
                        break;
                    }else{
                        targetRealmId.val("");
                    }
                }
            }else{
                targetRealmId.val("");
            }
        });

    },
    selectRealm: function(realmID, i) {
        $("#targetRealmId").val(realmID).click();
        $("#realm-type-ahead").val(RealmSelector.realmName[i]);
        $(".ui-typeahead").hide();
        $('#realmselect-dialog').dialog('close');
        $(".input-ghost").val('')
    },
    showRealmSelectDialog: function() {
        $('#realmselect-dialog').dialog('open');
        return false;
    },
    filterRealmDataTable: function() {
        RealmSelector.dataTableRealm.reset();

        var name = $("#name-filter").val();
        var realmType = $("#realm-type-filter").val();
        var realmLocale= $("#realm-locale-filter").val();

        if (name != "") {
            RealmSelector.dataTableRealm.filter('column', '1', name, 'default');
        }
        if (realmType != "") {
            RealmSelector.dataTableRealm.filter('column', '2', realmType, 'default');
        }
        if (realmLocale != "") {
            RealmSelector.dataTableRealm.filter('column', '3', realmLocale, 'default');
        }
    },
    resetRealmFilter: function() {
        $("#realm-type-filter").val('');
        $("#realm-locale-filter").val('');
        $("#name-filter").val('');

        RealmSelector.dataTableRealm.reset();

    }
};

$(document).ready(function() {
    RealmSelector.init();
});