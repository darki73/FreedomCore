/**
 * Dynamically create and position certain menus and sub menus (JSON objects) depending on specific conditions.
 */
var Menu = {

    /**
     * Base menu object data.
     */
    data: {},

    /**
     * Menu object data indexed by path.
     */
    dataIndex: {},

    /**
     * The main container element.
     */
    container: null,

    /**
     * The element that opened the drop down menu.
     */
    node: null,

    /**
     * Element ID to prepend to cached menus.
     */
    idName: 'menu-tier',

    /**
     * Wrapper class name for all sub menus.
     */
    className: 'flyout-menu',

    /**
     * Timer to kill the menu.
     */
    timer: null,

    /**
     * Collection of timers for children.
     */
    timers: {},

    /**
     * Timer to open/close the menu after a duration.
     */
    openTimer: null,

    /**
     * Currently opened children.
     */
    children: {},

    /**
     * Configuration.
     */
    config: {},

    /**
     * Initialize the class a store the container.
     *
     * @param url
     * @param config
     * @constructor
     */
    initialize: function(url, config) {
        Menu.config = $.extend({}, {
            duration: 750,
            dataUrl: {},
            colWidth: 200,
            colMax: 15
        }, config);

        Menu.container = $('<div/>').attr('id', 'menu-container').appendTo('body');
        Menu.container
            .unbind()
            .mouseleave(function() {
                Menu.timer = window.setTimeout(function() {
                    Menu.hide();
                }, Menu.config.duration);
            })
            .mouseenter(function() {
                window.clearTimeout(Menu.timer);
            });

        // If no data to fetch, exit early
        if (!url) {
            return false;
        }

        // Get the data
        Menu.load('base', url);

        // Bind the handlers
        $('.ui-breadcrumb li a').each(function(key, crumb) {
            var anchor = $(crumb),
                url = anchor.attr('href').replace(Core.baseUrl, '');

            anchor.mouseover(function() {
                Menu.show(this, url);
            });
        });
    },

    /**
     * Hide / reset the menu system.
     */
    hide: function() {
        window.clearTimeout(Menu.timer);
        window.clearTimeout(Menu.openTimer);

        Menu.container.find('div').hide();

        if (Menu.node) {
            Menu.node.removeClass('opened');
            Menu.node = null;
        }
    },

    /**
     * Hide the child node if it exists based on url.
     *
     * @param url
     */
    hideChild: function(url) {
        if (!Menu.children[url]) {
            return;
        }

        Menu.children[url]
            .children('a:first, span:first').removeClass('opened').end()
            .children('.' + Menu.className).hide();
    },

    /**
     * Load a dataset from a location.
     *
     * @param set
     * @param url
     */
    load: function(set, url) {
        if (Menu.data[set]) {
            return;
        }

        $.ajax({
            url: Core.baseUrl + url,
            dataType: 'json',
            success: function(data) {
                Menu.data[set] = data;
                Menu.dataIndex[set] = {};
                Menu._populate(data, set);

                // Add classes for specific situations
                var anchors = $('.ui-breadcrumb li a'),
                    length = anchors.length - 1;

                anchors.each(function(i) {
                    var self = $(this),
                        url = self.attr('href').replace(Core.baseUrl, ''),
                        idx = Menu.dataIndex[set][url];

                    if (idx) {
                        if (idx.children && length === i) {
                            self.parent().addClass('children');
                        }

                        if (!idx.children) {
                            self.parent().addClass('childless');
                        }
                    }
                });
            }
        });

        Menu.config.dataUrl[set] = url;
    },

    /**
     * An onclick alternative to show().
     *
     * @param node
     * @param path
     * @param options
     */
    open: function(node, path, options) {
        options = $.extend({}, { set: 'base' }, options || {});

        Menu.node = $(node);

        var data = Menu.dataIndex[options.set][path] || null;

        if (data && data.children) {
            if ($('#' + Menu._id(path, options.set) ).is(':visible')) {
                Menu.hide();
            } else {
                Menu._display(path, options);
            }
        }
    },

    /**
     * Show a specific menu at a specific location.
     * Used in conjunction with onmouseover.
     *
     * @param node
     * @param path
     * @param options
     */
    show: function(node, path, options) {
        options = $.extend({}, { set: 'base' }, options || {});

        if (!Menu.dataIndex[options.set][path]) {
            return;
        }

        if ($('#' + Menu._id(path, options.set) ).is(':visible')) {
            return;
        }

        Menu.hide();
        Menu.node = $(node);

        Menu.openTimer = window.setTimeout(function() {
            Menu._display(path, options);
        }, 200);

        Menu.node
            .unbind('mouseleave mouseenter')
            .mouseleave(function() {
                window.clearTimeout(Menu.openTimer);

                Menu.timer = window.setTimeout(function() {
                    Menu.hide();
                }, Menu.config.duration);
            })
            .mouseenter(function() {
                window.clearTimeout(Menu.timer);
            });
    },

    /**
     * Create the div/ul elements and append it to the parent.
     * Cycle through the links and create the li/a elements, and build children if available.
     *
     * @param parent - Parent node to add to
     * @param menu - Object containing children
     * @param cache
     */
    _build: function(parent, menu, cache) {
        var div = $('<div>').addClass(Menu.className),
            uls = [];

        if (cache) {
            div.attr('id', cache);
        }

        $.each(menu.children, function(key, data) {
            var tag = (data.url) ? 'a' : 'span',
                li = $('<li/>'),
                item = $('<' + tag + '/>', Menu._prepare(data)).appendTo(li);

            if (data.description) {
                item.append('<span class="desc">' + data.description + '</span>');
            }

            if (data.parentClass) {
                li.addClass(data.parentClass);
            }

            if (data.children) {
                item
                    .addClass('children')
                    .append('<span class="child-arrow"></span>');
            }

            li.hover(
                function() {
                    Menu.hideChild(menu.url);

                    if (data.children) {
                        var self = $(this);

                        self.children('a:first, span:first').addClass('opened');

                        if (self.find('.' + Menu.className).length === 0) {
                            Menu._build(this, data, false);
                        }

                        Menu._position(self.children('.' + Menu.className));

                        Menu.children[menu.url] = self;

                        window.clearTimeout(Menu.timers[menu.url]);
                    }
                },
                function() {
                    if (data.children) {
                        Menu.timers[menu.url] = window.setTimeout(function() {
                            Menu.hideChild(menu.url);
                        }, Menu.config.duration);
                    }
                }
            );

            // Determine which list
            var index = Math.ceil((key + 1) / Menu.config.colMax) - 1;

            if (menu.children.length <= (Menu.config.colMax + 3)) {
                index = 0;
            }

            if (!uls[index]) {
                uls[index] = $('<ul/>');
            }

            li.appendTo(uls[index]);
        });

        // Append
        for (var i = 0; i <= (uls.length - 1); ++i) {
            $(uls[i]).appendTo(div);
        }

        if (uls.length > 1) {
            div.css('width', (uls.length * Menu.config.colWidth));
        }

        div.appendTo(parent);
    },

    /**
     * Position the parent menu at the location.
     *
     * @param path
     * @param options
     */
    _display: function(path, options) {
        if (!Menu.dataIndex[options.set][path]) {
            return;
        }

        var data = Menu.dataIndex[options.set][path],
            center = (options.center) || (options === true),
            id = Menu._id(path, options.set);

        if (data && data.children) {
            var targetMenu = $('#' + id);

            if (targetMenu.length > 0) {
                targetMenu.fadeIn('fast');
            } else {
                Menu._build(Menu.container, data, id);
            }

            Menu.node.addClass('opened');

            // Position menu accordingly
            var nodeOffset = Menu.node.parent().offset(),
                nodeWidth = Menu.node.parent().width(),
                x = nodeOffset.left,
                y = nodeOffset.top + Menu.node.outerHeight(false),
                opts = {
                    top: y + 'px',
                    position: 'absolute',
                    visibility: 'visible',
                    zIndex: '75'
                };

            if (center) {
                var width = Math.round(nodeWidth / 2) - Math.round(Menu.config.colWidth / 2);
                opts.left = (x + width) + 'px';
            } else {
                opts.left = x + 'px';
            }

            Menu.container.css(opts).show();
        }
    },

    /**
     * Generate a DOM id.
     *
     * @param path
     * @param set
     */
    _id: function(path, set) {
        var id = Menu.idName + '-' + set;

        if (Menu.dataIndex[set][path].id) {
            id += Menu.dataIndex[set][path].id;
        }

        return id;
    },

    /**
     * Show the element, and reposition it if it goes off the page.
     *
     * @param element
     */
    _position: function(element) {
        element.show();

        if (element.find('ul').length <= 1) {
            var offset = element.offset(),
                width = element.outerWidth(false),
                x = offset.left + width;

            if (x >= Page.dimensions.width) {
                element.css('left', -width);
            }
        }
    },

    /**
     * Populate the class with data returned from the server.
     *
     * @param node
     * @param set
     */
    _populate: function(node, set) {
        if (!Menu.dataIndex[set][node.url]) {
            node.id = (node.url ? node.url.replace(/[^\-a-zA-Z0-9\/]/ig, '') : '');
            node.id = node.id.replace(/\//ig, '-');

            if (node.id.substr(-1) === '-') {
                node.id = node.id.substr(0, (node.id.length - 1));
            }

            Menu.dataIndex[set][node.url] = node;
        }

        if (node.children) {
            for (var i = 0, child; child = node.children[i]; i++) {
                Menu._populate(child, set);
            }
        }
    },

    /**
     * Prepare the element params based on a whitelist.
     *
     * @param obj
     */
    _prepare: function(obj) {
        var label = obj.label || '',
            mapping = { html: label.replace(/&/ig, '&amp;'), rel: 'np' },
            params = {};

        if (obj.url !== null) {
            mapping.href = Core.baseUrl + obj.url;
        }

        $.each(mapping, function(key, value) {
            if (value) {
                params[key] = value;
            }
        });

        return params;
    }

};