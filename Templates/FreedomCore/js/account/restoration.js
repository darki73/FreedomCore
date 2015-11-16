"use strict";
$(document).ready(function() {
    Restoration.initialize();
});
var Restoration = {
    config: {},
    selectedItems: {},
    itemRestorationTable: null,
    addItemList: null,
    selectedItemsHidden: null,
    deletedItemList: null,
    addedItemTotalCod: null,
    totalGold: null,
    totalSilver: null,
    totalCopper: null,
    totalDisenchantItems: null,
    totalCod: {
        gold: 0,
        silver: 0,
        copper: 0,
        materials: {},
        validCoins: ["gold", "silver", "copper"],
        clear: function() {
            this.gold = 0;
            this.silver = 0;
            this.copper = 0;
            this.materials = {};
        },
        getMaterialQuantity: function(a) {
            return this.materials[a];
        },
        addMaterial: function(a, b) {
            if (!this.materials[a]) {
                this.materials[a] = 0;
            }
            this.materials[a] += b;
        },
        removeMaterial: function(b, c) {
            var a = this.materials[b] - c;
            if (a < 0) {
                a = 0;
            }
            this.materials[b] = a;
        },
        hasCod: function() {
            return !!(this.gold || this.silver || this.copper);
        },
        hasMaterials: function() {
            if (!this.materials) {
                return false;
            }
            for (var a in this.materials) {
                if (this.materials[a]) {
                    return this.materials[a] !== 0;
                }
            }
            return false;
        }
    },
    initialize: function(a) {
        this.addedItemList = $("#added-item-list");
        this.selectedItemsHidden = $("#selected-items-hidden");
        this.deletedItemList = $("#deleted-item-list");
        this.addedItemTotalCod = $("#added-item-total");
        this.totalGold = this.addedItemTotalCod.children(".icon-gold");
        this.totalSilver = this.addedItemTotalCod.children(
            ".icon-silver");
        this.totalCopper = this.addedItemTotalCod.children(
            ".icon-copper");
        this.totalDisenchantItems = this.addedItemTotalCod.children(
            ".disenchant-needed-materials");
        var b = new ItemRestorationTable({
            results: 10
        });
        this.itemRestorationTable = b;
        this.deletedItemList.show();
        this.setup();
    },
    setup: function() {
        this.deletedItemList.on("click", ".item-row", function(b) {
            var a = $(this);
            Restoration.addItem(a.attr("data-instance"), a,
                true);
        });
    },
    calculateCod: function(b) {
        var a = this;
        a.totalCod.clear();
        a.totalCod.validCoins.forEach(function(c) {
            b.find('span[class="icon-' + c + '"]').each(
                function() {
                    var d = parseInt($(this).text());
                    if (d) {
                        a.totalCod[c] += d;
                    }
                });
        });
        b.find(".disenchant-needed-materials").each(function() {
            a.totalCod.addMaterial($(this).data("material-name"),
                $(this).data("material-quantity"));
        });
        this.updateTotalCodElement();
    },
    updateTotalCodElement: function() {
        this.addedItemTotalCod.removeClass("hide");
        if (!(this.totalCod.hasCod() || this.totalCod.hasMaterials())) {
            this.addedItemTotalCod.addClass("hide");
        }
        this.totalGold.text(this.totalCod.gold);
        this.totalSilver.text(this.totalCod.silver);
        this.totalCopper.text(this.totalCod.copper);
        this.totalDisenchantItems.html("");
        if (this.totalCod.materials) {
            for (var b in this.totalCod.materials) {
                var a = this.totalCod.materials[b] + " " + b;
                this.totalDisenchantItems.append("<div>" + a + "</div>");
            }
        }
    },
    addItem: function(e, d, g) {
        var c = this;
        var f, b = $(d);
        if (Restoration.selectedItems[e] !== true) {
            b.find('input[type="checkbox"]').attr("checked", "checked");
            this.selectedItemsHidden.append(b.clone().wrap("<div>").parent()
                .html());
            f = $(".item", b).html();
            this.addedItemList.append(f);
            this.calculateCod(this.addedItemList);
            if (g) {
                var a = $(".icon-frame", b);
                a.css("position", "relative").animate({
                    right: "-=750px",
                    top: "-=180px",
                    opacity: "0"
                }, 250, function() {
                    Restoration.hideItem(e, b);
                    a.css({
                        right: 0,
                        top: 0,
                        opacity: 1
                    });
                });
            } else {
                Restoration.hideItem(e, b);
            }
            Restoration.selectedItems[e] = true;
            $("#selected-continue").show();
        }
    },
    hideItem: function(d, a) {
        var c = parseInt(a.data("rowid")),
            b = Restoration.itemRestorationTable.tableSorter,
            e = b.pager.page,
            f;
        for (f in b.source) {
            if (b.source[f][2].attr("data-instance") == d) {
                b.source[f][0][7] = "0";
                b.filter("column", 7, "0", "greaterThan");
                break;
            }
        }
        if (b.pager.totalPages < e) {
            b.paginate(b.pager.totalPages);
        } else {
            b.paginate(e);
        }
    },
    updateSelectedItems: function(d) {
        var a, c, b = Restoration.itemRestorationTable.tableSorter;
        for (a = 0; a < d.length; a++) {
            c = d[a];
            Restoration.addItem(c, $(".item-row[data-instance=" + c +
                "]")[0], false);
        }
    },
    removeItem: function(e) {
        var a, d, g = 0,
            c, b = Restoration.itemRestorationTable.tableSorter,
            f;
        $("div[data-instance=" + e + "]", "#added-item-list").remove();
        var a = $("tr[data-instance=" + e + "]",
            "#selected-items-hidden");
        d = parseInt(a.data("rowid"));
        f = b.pager.page;
        b.source[d][0][7] = "1";
        b.filter("column", 7, "0", "greaterThan");
        b.paginate(f);
        a.remove();
        $("input[data-instanceid=" + e + "]").removeAttr("checked");
        Restoration.selectedItems[e] = false;
        for (c in Restoration.selectedItems) {
            if (Restoration.selectedItems[c] === true) {
                g++;
            }
        }
        if (g === 0) {
            $("#selected-continue").hide();
        }
        this.calculateCod(this.addedItemList);
    },
    removeManualItems: function(b, a) {
        $.ajax({
            type: "POST",
            timeout: 60000,
            url: "/support/" + Core.locale.split("-")[0] +
            "/restoration/json/remove-manual-items",
            ifModified: false,
            global: false,
            context: this,
            success: function(c) {
                if (c.success) {
                    $(".manual-restore").hide();
                    $(".manual-restore-note-title").hide();
                    $(".manual-restore-note-desc").hide();
                    if (c.remainingItems === 0) {
                        window.location.href = "/support/" +
                            Core.locale.split("-")[0] +
                            "/restoration/select-items?r=" +
                            b + "&c=" + a;
                    }
                }
            }
        });
        return false;
    },
    flushCharacterList: function(a) {
        $.ajax({
            type: "POST",
            timeout: 60000,
            url: "/support/" + Core.locale.split("-")[0] +
            "/wow/json/characters/flush",
            data: {
                realmId: a
            },
            ifModified: false,
            global: false,
            context: this,
            success: function(b) {
                location.reload(true);
            }
        });
        return false;
    },
};
var ItemRestorationTable = Class.extend({
    table: null,
    rows: null,
    links: null,
    dateTime: null,
    tableSorter: null,
    filterSelect: null,
    filterLink: null,
    regionSelect: null,
    filter: "",
    nameFilter: null,
    minFilter: null,
    maxFilter: null,
    rarityFilter: null,
    numericFieldKeycodes: [],
    config: {},
    init: function(a) {
        a = typeof a === "object" ? a : {};
        this.config = $.extend({
            sortable: true,
            filterable: true,
            results: 10
        }, a);
        this.setup();
    },
    setup: function() {
        var a = this.config;
        var b;
        this.table = $("#deleted-item-list");
        this.rows = this.table.find("tbody tr");
        this.dateTime = new DateTime();
        this.dateTime.localize();
        this.tableSorter = DataSet.factory("#deleted-item-dataset", {
            paging: true,
            results: a.results,
            elementControls: ".table-options"
        });
        this.filterSelect = $("#filter-select");
        this.filterLink = $("#filter-apply");
        this.filterCancelLink = $("#filter-cancel");
        this.regionSelect = $("#region-select");
        this.nameFilter = $("#filter-name");
        this.minFilter = $("#filter-range-min");
        this.maxFilter = $("#filter-range-max");
        this.rarityFilter = $("#filter-rarity");
        this.categoryFilter = $("#filter-category");
        for (b = 48; b < 58; ++b) {
            this.numericFieldKeycodes.push(b);
        }
        this.numericFieldKeycodes.push(8);
        this.numericFieldKeycodes.push(13);
        this.numericFieldKeycodes.push(0);
        this.applyFilter();
        this.nameFilter.bind("keydown", $.proxy(this.interceptEnter,
            this));
        this.minFilter.bind("keydown", $.proxy(this.interceptEnter,
            this));
        this.maxFilter.bind("keydown", $.proxy(this.interceptEnter,
            this));
        $("#filter-range-min, #filter-range-max").keypress($.proxy(
            this.restrictNumericField, this));
        this.filterCancelLink.live("click", {
            history: this
        }, function(c) {
            c.data.history.nameFilter.val("");
            c.data.history.minFilter.val("");
            c.data.history.maxFilter.val("");
            c.data.history.rarityFilter.val(1);
            c.data.history.categoryFilter.val(-1);
            c.data.history.applyFilter();
            return false;
        });
        this.filterLink.live("click", {
            history: this
        }, function(c) {
            c.data.history.applyFilter();
            return false;
        });
    },
    restrictNumericField: function(a) {
        if (!(this.numericFieldKeycodes.indexOf(a.which) >= 0)) {
            a.preventDefault();
        }
    },
    interceptEnter: function(a) {
        if (a.keyCode === 13) {
            this.applyFilter();
            return false;
        }
    },
    applyFilter: function() {
        var b, a;
        this.tableSorter.filter("class", "class", "");
        if (this.nameFilter.val() !== "") {
            this.tableSorter.filter("column", 1, this.nameFilter.val());
        }
        if (this.minFilter.val() !== "" && this.maxFilter.val() !==
            "") {
            b = Math.min(parseInt(this.minFilter.val()), parseInt(
                this.maxFilter.val()));
            a = Math.max(parseInt(this.minFilter.val()), parseInt(
                this.maxFilter.val()));
            this.tableSorter.filter("column", 2, [b, a], "range");
        } else {
            if (this.minFilter.val() !== "") {
                this.tableSorter.filter("column", 2, (parseInt(this
                    .minFilter.val()) - 1).toString(),
                    "greaterThan");
            } else {
                if (this.maxFilter.val() !== "") {
                    this.tableSorter.filter("column", 2, (parseInt(
                        this.maxFilter.val()) + 1).toString(),
                        "lessThan");
                }
            }
        } if (this.categoryFilter.val() !== "" && this.categoryFilter
                .val() !== "-1") {
            this.tableSorter.filter("column", 8, this.categoryFilter
                .val());
        }
        if (this.rarityFilter.val() !== "") {
            this.tableSorter.filter("column", 6, (parseInt(this.rarityFilter
                .val()) - 1).toString(), "greaterThan");
        }
        this.tableSorter.filter("column", 7, "0", "greaterThan");
    }
});