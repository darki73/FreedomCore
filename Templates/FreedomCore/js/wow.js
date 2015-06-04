$(function() {
    Wow.initialize();
    Fansite.initialize();
    //  Temporary override of Common to disable the login modal dialog and redirect to the login page instead.
    Login.open = function() {
        window.location = "/fragment/login.frag?returnto="+encodeURIComponent(document.referrer);
    };
});
var Wow = {
    /**
     * Initialize all wow tooltips.
     *
     * @constructor
     */
    initialize: function() {
        this.items = [];
        setTimeout(function() {
            Wow.bindTooltips('achievement');
            Wow.bindTooltips('spell');
            Wow.bindTooltips('quest');
            Wow.bindTooltips('currency');
            Wow.bindTooltips('zone');
            Wow.bindTooltips('scenario');
            Wow.bindTooltips('faction');
            Wow.bindTooltips('npc');
            Wow.bindItemTooltips();
            Wow.bindPetTooltips();
            Wow.bindCharacterTooltips();
            Wow.bindGuildTooltips();
        }, 1);
        CharSelect.beforeCallback = Wow.CharSelect.before;
        CharSelect.afterCallback = Wow.CharSelect.after;
    },
    /**
     * Display or hide the video.
     */
    toggleInterceptVideo: function() {
        $("#video, #blackout, #play-trailer").toggle();
        return false;
    },
    /**
     * Bind Pet and Pet ability tooltips
     */
    bindPetTooltips: function() {
        // Pet ability
        Tooltip.factory('[data-pet-ability]', {
            ajax: true,
            onShow: function(event) {
                var node = event.currentTarget,
                    $node = $(node);
                if (node.rel == 'np') return;
                var abilityData = $node.data('pet-ability');
                var info = $node.data('pet-info');
                var petInfo = (typeof info != 'undefined') ? "?data=" +
                encodeURIComponent(info) : "";
                if (typeof abilityData != 'undefined') {
                    Tooltip.show(node, '/pet/ability/' + abilityData + '/tooltip' +
                    petInfo, true);
                }
            }
        });
        // Pet type / family
        Tooltip.factory('[data-pet-type]', {
            ajax: true,
            onShow: function(event) {
                var node = event.currentTarget,
                    $node = $(node);
                if (node.rel == 'np') return;
                var data = $node.data('pet-type');
                if (typeof data != 'undefined') {
                    Tooltip.show(this, '/pet/type/' + data + '/tooltip', true);
                }
            }
        });
    },
    /**
     * Bind item tooltips to links.
     * Gathers the item ID from the href, and the optional params from the data-item attribute.
     */
    bindItemTooltips: function() {
        Tooltip.factory('a[href*="/item/"], [data-item]', {
            ajax: true,
            onShow: function(event) {
                var node = event.currentTarget;
                if (node.rel == 'np') {
                    return;
                }
                var id = node.pathname.split(Core.baseUrl + '/item/')[1];
                var data = node.getAttribute('data-item');
                var query = "";
                if (id) {
                    if (node.search) {
                        query = node.search;
                    }
                    if (data) {
                        query = (query ? '&' : '?') + data;
                    }
                } else {
                    id = data;
                }
                if (id && parseInt(id) > 0) {
                    Tooltip.show(node, '/item/' + id + '/tooltip' + query, true)
                }
            }
        });
    },
    /**
     * Bind character tooltips to links.
     * Add rel="np" to disable character tooltips on links.
     */
    bindCharacterTooltips: function() {
        Tooltip.factory('a[href*="/character/"]', {
            ajax: true,
            onShow: function(event) {
                var node = event.currentTarget,
                    $node = $(node);
                if (node.href == 'javascript:;' || node.href.indexOf('#') == 0 || node
                        .rel == 'np' || node.href.indexOf('/vault/') != -1) return;
                var href = $node.attr('href').replace(Core.baseUrl + '/character/', "")
                    .split('/');
                if (location.href.toLowerCase().indexOf('/' + href[1].toLowerCase() +
                    '/') != -1 && this.rel != 'allow') return;
                Tooltip.show(node, '/' + href[1] + '/' + encodeURIComponent(href[2]) + '/tooltip', true);
            }
        });
    },
    /**
     * Bind Guild tooltips to links.
     * Add rel="np" to disable character tooltips on links.
     */
    bindGuildTooltips: function() {
        Tooltip.factory('a[href*="/guild/"]', {
            ajax: true,
            onShow: function(event) {
                var node = event.currentTarget,
                    $node = $(node);
                if (node.href == 'javascript:;' || node.href.indexOf('#') == 0 || node
                        .rel == 'np') return;
                var href = $node.attr('href').replace(Core.baseUrl + '/guild/', "").split(
                    '/');
                if (location.href.toLowerCase().indexOf('/' + href[1].toLowerCase() +
                    '/') != -1 && this.rel != 'allow') return;
                Tooltip.show(node, '/' + href[1] + '/' + encodeURIComponent(href[2]) + '/tooltip', true);
            }
        });
    },
    /**
     * Bind a tooltip to a specific wiki type.
     *
     * @param type
     */
    bindTooltips: function(type) {
        Tooltip.factory('[data-' + type + ']', {
            ajax: true,
            onShow: function(event) {
                var node = event.currentTarget,
                    $node = $(node);
                if (node.rel == 'np') return;
                var data = $node.data(type);
                if (typeof data != 'undefined') {
                    if (type == 'scenario') {
                        // Use the same tooltip as zones, but add scenarios so the IDs don't conflict.
                        Tooltip.show(node, '/zone/' + type + '/' + data + '/tooltip', true);
                    } else if (type == 'spell') {
                        // Check for spell-item for scaled item spells
                        var itemData = $node.data('spell-item');
                        var query = '';
                        if (itemData) {
                            query = '?itemId=' + itemData;
                        }
                        Tooltip.show(node, '/' + type + '/' + data + '/tooltip' + query,
                            true);
                    } else {
                        Tooltip.show(node, '/' + type + '/' + data + '/tooltip', true);
                    }
                }
            }
        });
    },
    /**
     * Update the events within the sidebar.
     *
     * @param id
     * @param status
     */
    updateEvent: function(id, status) {
        $('#event-' + id + ' .actions').fadeOut('fast');
        $.ajax({
            url: $('.profile-link').attr('href') + 'event/' + status,
            data: {
                eventId: id
            },
            dataType: "json",
            success: function(data) {
                $('#event-' + id).fadeOut('fast', function() {
                    $(this).remove();
                });
            }
        });
        return false;
    },
    /**
     * Load the browse.json data and display the dropdown menu.
     *
     * @param node
     * @param url
     */
    browseArmory: function(node, url) {
        if ($('#menu-tier-browse').is(':visible')) return;
        Menu.load('browse', url);
        Menu.show(node, '/', {
            set: 'browse'
        });
    },
    /**
     * Creates the html nodes for basic tooltips.
     *
     * @param title
     * @param description
     * @param icon
     */
    createSimpleTooltip: function(title, description, icon) {
        var $tooltip = $('<ul/>');
        if (icon) {
            $('<li/>').append(Wow.Icon.framedIcon(icon, 56)).appendTo($tooltip);
        }
        if (title) {
            $('<li/>').append($('<h3/>').text(title)).appendTo($tooltip);
        }
        if (description) {
            $('<li/>').addClass('color-tooltip-yellow').html(description).appendTo(
                $tooltip);
        }
        return $tooltip;
    },
    /**
     * Add new BML commands to the editor.
     */
    addBmlCommands: function() {
        BML.addCommands([{
            type: 'item',
            tag: 'item',
            filter: true,
            selfClose: true,
            prompt: Msg.bml.itemPrompt,
            pattern: ['\\[item="([0-9]{1,5})"\\s*/\\]'],
            result: ['<a href="' + Core.baseUrl + '/item/$1">' + Core.host + Core.baseUrl +
            '/item/$1</a>'
            ]
        }]);
    }
};
Wow.Icon = {
    /**
     * Generate icon path.
     *
     * @param name
     * @param size
     * @param grey
     */
    getUrl: function(name, size, grey) {
        var iconSize = 56;
        var path = '/wow/icons/';
        if (size <= 18) iconSize = 18;
        else if (size <= 36) iconSize = 36;
        if (grey) {
            path = '/wow/icons-grey/';
        }
        return Core.cdnUrl + path + iconSize + '/' + name + '.jpg';
    },
    /**
     * Create frame icon markup.
     *
     * @param name
     * @param size
     * @param grey
     */
    framedIcon: function(name, size, grey) {
        var $icon = $('<span/>').addClass('icon-frame frame-' + size);
        if (grey) {
            $icon.addClass("grey");
        }
        if (size == 18 || size == 36 || size == 56) {
            $icon.css('background-image', 'url(' + Wow.Icon.getUrl(name, size, grey) +
            ')');
        } else {
            $icon.append($('<img/>').attr({
                width: size,
                height: size,
                src: Wow.Icon.getUrl(name, size)
            }));
        }
        return $icon;
    }
};
Wow.CharSelect = {
    /**
     * Char select before callback.
     *
     * @param node
     */
    before: function(node) {
        if (location.pathname.indexOf('/character/') !== -1) {
            if (location.pathname.indexOf('/vault/') !== -1) {
                location.reload(true);
            } else {
                Wow.CharSelect.redirect(node.href);
            }
            return true;
        }
    },
    /**
     * Char select after callback.
     */
    after: function() {
        var redirectTo;
        if (location.href.indexOf('/character/') !== -1) {
            redirectTo = $('#user-plate a.character-name').attr('href');
        } else if (location.href.indexOf('/guild/') !== -1) {
            redirectTo = $('#user-plate a.guild-name').attr('href');
            // Deal with guild-less characters
            if (!redirectTo) {
                location.href = $('#user-plate a.character-name').attr('href');
            }
        }
        if (redirectTo) {
            Wow.CharSelect.redirect(redirectTo);
        }
    },
    /**
     * Redirecting logic.
     *
     * @param url
     */
    redirect: function(url) {
        // Vault-secured pages only need to be refreshed
        if (url.indexOf('/vault/') !== -1) {
            location.reload(true);
        }
        // Preserve current page
        var page = '';
        if (location.href.match(/\/(character|guild)\/.+?\/.+?\/(.+)$/)) {
            page = RegExp.$2;
            // Ignore pages that aren't always available
            $.each(['pet', 'profession'], function() {
                if (page.indexOf(this) !== -1) {
                    page = '';
                }
            });
        }
        location.href = url + page;
    }
};
/**
 * 3rd-party fansite integration.
 */
var Fansite = {
    /**
     * Map of sites and available URLs.
     */
    sites: {
        wowhead: {
            name: 'Wowhead',
            site: 'http://www.wowhead.com/',
            regions: ['us', 'eu'],
            locales: ['de', 'es', 'fr', 'ru', 'it'],
            urls: {
                achievement: ['achievements', 'achievement={0}'],
                character: ['profiles',
                    function(params) {
                        var region = params[1].toLowerCase();
                        var realm = params[3].toLowerCase();
                        realm = realm.replace(/( )+/g, '-')
                        realm = realm.replace(/^A-Z/ig, '')
                        var name = params[2].toLowerCase();
                        return 'profile=' + encodeURIComponent(region) + '.' +
                            encodeURIComponent(realm) + '.' + encodeURIComponent(name);
                    }
                ],
                faction: ['factions', 'faction={0}'],
                'class': ['classes', 'class={0}'],
                object: ['objects', 'object={0}'],
                skill: ['skills', 'skill={0}'],
                race: ['races', 'race={0}'],
                quest: ['quests', 'quest={0}'],
                spell: ['spells', 'spell={0}'],
                event: ['events', 'event={0}'],
                title: ['titles', 'title={0}'],
                zone: ['zones', 'zone={0}'],
                item: ['items', 'item={0}'],
                npc: ['npcs', 'npc={0}'],
                pet: ['pets', 'pet={0}']
            }
        },
        wowpedia: {
            name: 'Wowpedia',
            site: 'http://www.wowpedia.org/',
            regions: ['us', 'eu'],
            locales: ['fr', 'es', 'de', 'ru'],
            domains: {
                ru: 'http://wowpedia.ru/wiki/'
            },
            urls: {
                faction: ['Factions', '{1}'],
                'class': ['Classes', '{1}'],
                skill: ['Professions', '{1}'],
                race: ['Races', '{1}'],
                zone: ['Zones', '{1}'],
                item: ['Items', '{1}'],
                pet: ['Pets', '{1}'],
                npc: ['NPCs', '{1}']
            },
            buildUrl: function(params) {
                return params[2].replace(/\s+/g, '_').replace(/"/ig, '&quot;');
            }
        },
        judgehype: {
            name: 'JudgeHype',
            site: 'http://worldofwarcraft.judgehype.com/',
            regions: ['eu'],
            locales: ['fr'],
            urls: {
                achievement: ['?page=hautsfaits', '?page=hautfait&amp;w={0}'],
                faction: ['?page=reputations', '?page=reputation&amp;w={0}'],
                'class': ['?page=racesclasses',
                    function(params) {
                        return '?page=' + encodeURIComponent(params[1].toLowerCase());
                    }
                ],
                skill: ['?page=professions',
                    function(params) {
                        return '?page=' + encodeURIComponent(params[1].toLowerCase());
                    }
                ],
                quest: ['?page=quetes', '?page=quete&amp;w={0}'],
                spell: ['?page=spell', '?page=spell&amp;w={0}'],
                zone: ['?page=zones', '?page=zone&amp;w={0}'],
                item: ['?page=objets', '?page=objet&amp;w={0}'],
                race: ['?page=factions',
                    function(params) {
                        return '?page=' + encodeURIComponent(params[1].toLowerCase());
                    }
                ],
                npc: ['?page=pnjs', '?page=pnj&amp;w={0}'],
                pet: ['?page=pnjs-betes', '?page=pnjs-bete&amp;w={0}']
            }
        },
        buffed: {
            name: 'Buffed.de',
            site: 'http://wowdata.buffed.de/',
            regions: ['eu'],
            locales: ['de'],
            urls: {
                achievement: ['', '?a={0}'],
                faction: ['faction/', '?faction={0}'],
                'class': ['class/portal', 'class/portal/{0}'],
                skill: ['', 'spell/profession/{0}'],
                spell: ['', '?s={0}'],
                title: ['title/list', 'title/list'],
                quest: ['quest/list/1/', '?q={0}'],
                item: ['item/list', '?i={0}'],
                zone: ['zone/list/1/', '?zone={0}'],
                npc: ['', '?n={0}']
            }
        },
        figureprints: {
            name: 'FigurePrints',
            site: 'http://figureprints.com/',
            regions: ['us', 'eu'],
            locales: ['fr', 'es', 'de', 'ru', 'it'],
            urls: {
                character: ['Characters',
                    function(params) {
                        var name = params[2];
                        var realm = params[3];
                        var region = params[1];
                        return 'CharacterList.aspx?n=' + encodeURIComponent(name) + '&amp;r=' +
                            encodeURIComponent(realm) + '&amp;e=' + encodeURIComponent(region);
                    }
                ]
            }
        }
    },
    /**
     * Map of content types and available sites for that type.
     */
    map: {
        achievement: ['wowhead', 'buffed', 'judgehype'],
        character: ['figureprints', 'wowhead'],
        faction: ['wowhead', 'wowpedia', 'buffed', 'judgehype'],
        'class': ['wowhead', 'wowpedia', 'buffed', 'judgehype'],
        object: ['wowhead'],
        skill: ['wowhead', 'wowpedia', 'buffed', 'judgehype'],
        quest: ['wowhead', 'buffed', 'judgehype'],
        spell: ['wowhead', 'buffed', 'judgehype'],
        event: ['wowhead'],
        title: ['wowhead', 'buffed'],
        arena: [],
        guild: [],
        zone: ['wowhead', 'wowpedia', 'buffed', 'judgehype'],
        item: ['wowhead', 'wowpedia', 'buffed', 'judgehype'],
        race: ['wowhead', 'wowpedia', 'judgehype'],
        npc: ['wowhead', 'wowpedia', 'buffed', 'judgehype'],
        pet: ['wowhead', 'wowpedia', 'judgehype'],
        pvp: []
    },
    /**
     * Create the menu HTML and delegate link events.
     *
     * @constructor
     */
    initialize: function() {
        if (Fansite.initialized) {
            return;
        }
        Fansite.initialized = true;
        $(document).delegate('a[data-fansite]', 'mouseenter.fansite', Fansite.onMouseOver)
            .delegate('a[data-fansite]', 'mouseleave.fansite', ContextMenu.delayedHide);
    },
    onMouseOver: function() {
        var node = $(this),
            params = Fansite.read(node.data('fansite'));
        Fansite.openMenu(node, params);
        return false;
    },
    /**
     * Split params the awesome way!
     *
     * @param data
     * @return array
     */
    read: function(data) {
        return data.split('|');
    },
    /**
     * Generate links from params.
     *
     * @param params
     * @return array
     */
    createLinks: function(params) {
        var type = params[0],
            map = Fansite.map[type],
            links = [],
            lang = Core.getLanguage();
        if (map.length > 0) {
            var site, url, urls;
            for (var i = 0, len = map.length; i < len; ++i) {
                if (!Fansite.sites[map[i]]) continue;
                site = Fansite.sites[map[i]];
                if (
                    ((lang != 'en') && ($.inArray(lang, site.locales) < 0)) || ($.inArray(
                        Core.region, site.regions) < 0) || !site.urls[type]) {
                    continue;
                }
                url = Fansite.createUrl(site),
                    urls = site.urls[type];
                if (params.length <= 1) {
                    url += urls[0];
                } else {
                    if (typeof site.buildUrl == 'function') {
                        url += site.buildUrl(params);
                    } else {
                        var urlPattern = urls[1];
                        if (typeof urlPattern == 'function') {
                            url += urlPattern(params);
                        } else {
                            for (var j = 1; j < params.length; ++j) {
                                urlPattern = urlPattern.replace('{' + (j - 1) + '}',
                                    encodeURIComponent(params[j]));
                            }
                            url += urlPattern;
                        }
                    }
                }
                links.push('<a href="' + url + '" target="_blank">' + site.name + '</a>');
            }
        }
        return links;
    },
    /**
     * Create the URL based on locale.
     *
     * @param site
     * @return string
     */
    createUrl: function(site) {
        var url = site.site,
            lang = Core.getLanguage();
        if ($.inArray(lang, site.locales) >= 0) {
            if (site.domains && site.domains[lang]) url = site.domains[lang];
            else url = url.replace('www', lang);
        }
        return url;
    },
    /**
     * Open up the menu and show the available sites for that type.
     *
     * @param node
     * @param params
     */
    openMenu: function(node, params) {
        Fansite.node = node;
        var list = $('<ul/>');
        var links = Fansite.createLinks(params);
        var title = '';
        if (links.length == 0) {
            title = Msg.ui.fansiteNone;
        } else {
            if (Msg.fansite[params[0]]) {
                title = Msg.ui.fansiteFindType.replace('{0}', Msg.fansite[params[0]]);
            } else {
                title = Msg.ui.fansiteFind;
            }
        }
        $('<li/>').addClass('divider').html('<span>' + title + '</span>').appendTo(
            list);
        if (links.length > 0) {
            for (var i = 0, length = links.length; i < length; ++i) {
                $('<li/>').append(links[i]).appendTo(list);
            }
            // Also linkify the button itself if there's only 1 fansite
            if (links.length == 1) {
                node.attr('href', $(links[0]).attr('href'));
                node.attr('target', '_blank');
            }
        }
        ContextMenu.show(node, list);
    },
    /**
     * Generate links for inline display.
     *
     * @param target
     * @param data
     */
    generate: function(target, data) {
        var links = Fansite.createLinks(Fansite.read(data));
        if (links.length > 0) {
            $(target).html(links.join(' ')).addClass('fansite-group');
        } else {
            $(target).parent('.snippet').remove();
            return false;
        }
    }
};
/* Show a custom contextual menu at the desired location */
var ContextMenu = {
    DELAY_HIDE: 333,
    // DOM
    object: null,
    node: null,
    parentNode: null,
    cb: null,
    initialize: function() {
        if (ContextMenu.object != null) {
            return;
        }
        ContextMenu.object = $('<div/>').attr('id', 'context-menu').addClass(
            'flyout-menu').appendTo('body').mouseenter(ContextMenu.onMouseOver).mouseleave(
            ContextMenu.onMouseOut);
    },
    show: function(node, contents) {
        if (ContextMenu.parentNode != null) {
            ContextMenu.parentNode.removeClass('hover');
        }
        clearTimeout(ContextMenu.timer);
        node = $(node);
        ContextMenu.node = node;
        ContextMenu.parentNode = node.parent();
        ContextMenu.initialize();
        ContextMenu.object.html(contents);
        ContextMenu.position(node);
        ContextMenu.parentNode.addClass('hover');
    },
    onMouseOver: function() {
        clearTimeout(ContextMenu.timer);
    },
    onMouseOut: function() {
        ContextMenu.hide();
    },
    delayedHide: function() {
        clearTimeout(ContextMenu.timer);
        ContextMenu.timer = setTimeout(ContextMenu.hide, ContextMenu.DELAY_HIDE);
    },
    /**
     * Hide the menu.
     */
    hide: function() {
        ContextMenu.object.hide();
        if (ContextMenu.parentNode != null) {
            ContextMenu.parentNode.removeClass('hover');
        }
        ContextMenu.node = null;
        ContextMenu.parentNode = null;
    },
    /**
     * Position the menu at the middle right.
     *
     * @param node
     */
    position: function(node) {
        var offset = node.offset(),
            nodeWidth = node.outerWidth(),
            nodeHeight = node.outerHeight(),
            winWidth = ($(window).width() / 3),
            width = ContextMenu.object.outerWidth(),
            height = ContextMenu.object.outerHeight(),
            y = (offset.top + (nodeHeight / 2)) - (height / 2),
            x;
        if (offset.left > (winWidth * 2)) x = (offset.left - width) - 10;
        else x = offset.left + nodeWidth;
        ContextMenu.object.css({
            top: y,
            left: x + 5
        }).fadeIn('fast');
    }
};