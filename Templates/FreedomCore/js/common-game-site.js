// Place use strict in the first file so that aggregation inherits it
"use strict";
/**
 * Application related functionality.
 */
var App = {
	/**
	 * Enable the explore links.
	 *
	 * @constructor
	 */
	initialize: function() {
		var links = $('a[rel="javascript"]');
		if (links.length) {
			links.removeAttr('onclick').removeAttr('onmouseover').removeAttr('title').css(
				'cursor', 'pointer');
		}
		var supportLink = $('#support-link'),
			exploreLink = $('#explore-link'),
			newsLink = $('#breaking-link'),
			authLink = $('#auth-link');
		if (supportLink.length > 0) {
			supportLink.unbind().click(function() {
				Tickets.loadStatus();
				Toggle.open(this, 'active', '#support-menu');
				return false;
			});
		}
		if (authLink.length > 0) {
			authLink.unbind().click(function() {
				Marketing.trackImpression('Battle.net Authenticator', 'Menu Toggle', $(
					'#auth-menu').is(':hidden') ? 'Open' : 'Close');
				Toggle.open(this, 'active', '#auth-menu');
				return false;
			});
			$('#auth-menu a').click(function() {
				var label;
				if (this.className === 'auth-close') {
					Toggle.open(authLink, 'active', '#auth-menu');
					authLink.parent().hide();
					label = 'Close';
				} else if (this.className === 'auth-button') {
					label = 'Add';
				} else {
					label = 'More';
				}
				Marketing.trackImpression('Battle.net Authenticator', 'Link Click',
					label);
				Cookie.create('serviceBar.authCheck', 1, {
					expires: 744, // 1 month of hours
					path: '/'
				});
			});
			Marketing.trackImpression('Battle.net Authenticator', 'Impression',
				'Impression');
		}
		if (exploreLink.length > 0) {
			exploreLink.unbind().click(function() {
				Toggle.open(this, 'active', '#explore-menu');
				return false;
			});
		}
		if (newsLink.length > 0) {
			newsLink.unbind().click(function() {
				App.breakingNews();
				return false;
			});
		}
	},
	/**
	 * Hide the service bar warnings.
	 *
	 * @param target
	 * @param cookie
	 */
	closeWarning: function(target, cookie) {
		$(target).hide();
		if (cookie) App.saveCookie(cookie);
	},
	/**
	 * Open and close the breaking news.
	 *
	 * @param lastId
	 */
	breakingNews: function(lastId) {
		var node = $("#breaking-news");
		var news = $("#announcement-warning");
		if (news.is(':visible')) {
			news.hide();
			node.removeClass('opened');
		} else {
			news.show();
			node.addClass('opened');
		}
		if (lastId) {
			Cookie.create('serviceBar.breakingNews', lastId);
		}
	},
	/**
	 * Save a cookie.
	 *
	 * @param name
	 */
	saveCookie: function(name) {
		Cookie.create('serviceBar.' + name, 1, {
			expires: 8760, // 1 year of hours
			path: '/'
		});
	},
	/**
	 * Reset a cookie.
	 *
	 * @param name
	 */
	resetCookie: function(name) {
		Cookie.create('serviceBar.' + name, 0, {
			expires: 8760, // 1 year of hours
			path: '/'
		});
	},
	/**
	 * Hide service bar elements depending on cookies.
	 */
	serviceBar: function() {
		var browser = Cookie.read('serviceBar.browserWarning');
		var locale = Cookie.read('serviceBar.localeWarning');
		if (browser === 1) $('#browser-warning').hide();
		if (locale === 1) $('#i18n-warning').hide();
	},
	/**
	 * Values for sidebar module loading.
	 */
	totalModules: 0,
	totalLoaded: 0,
	modules: [],
	forceLoad: true,
	/**
	 * Dynamically load more than one sidebar module at a time.
	 *
	 * @param modules
	 */
	sidebar: function(modules) {
		App.totalModules = modules.length;
		if (modules.length) {
			for (var i = 0; i <= (modules.length - 1); ++i) {
				App.loadModule(modules[i], i);
			}
		}
		// Show the modules after 5 seconds incase some are hanging
		window.setTimeout(function() {
			if (App.forceLoad) {
				App.showSidebar();
			}
		}, 5000);
	},
	/**
	 * Show the sidebar modules.
	 */
	showSidebar: function() {
		App.forceLoad = false;
		var dynamicSidebarTarget = $("#dynamic-sidebar-target");
		var sidebar = $('#sidebar .sidebar-bot');
		for (var i = 0; i < App.totalModules; i++) {
			if (App.modules[i]) {
				App.modules[i].appendTo(dynamicSidebarTarget);
			}
		}
		$('#sidebar-loading').fadeOut('normal', function() {
			sidebar.find('.sidebar-module').fadeIn();
			$(this).remove();
		});
		// Reset
		App.modules = [];
		App.totalModules = 0;
		App.totalLoaded = 0;
	},
	/**
	 * Load the content of a sidebar module through AJAX.
	 *
	 * @param module
	 * @param index
	 */
	loadModule: function(module, index) {
		var dynamicSidebarTarget = $("#dynamic-sidebar-target");
		$.ajax({
			url: Core.baseUrl + '/sidebar/' + module.type + (module.query || ""),
			type: 'GET',
			dataType: 'html',
			cache: true,
			global: false,
			success: function(data) {
				App.totalLoaded++;
				if ($.trim(data) !== "") {
					var node = $(data);
					if (App.forceLoad) {
						node.hide();
						App.modules[index] = node;
					} else {
						node.appendTo(dynamicSidebarTarget);
					}
				}
			},
			error: function() {
				App.totalLoaded++;
			},
			complete: function() {
				if (App.totalLoaded >= App.totalModules) {
					window.setTimeout(App.showSidebar, 100);
				}
			}
		});
	}
};
$(function() {
	App.initialize();
});
/**
 * Creates a full page blackout.
 */
var Blackout = {
	/**
	 * Has the blackout been opened before?
	 */
	initialized: false,
	/**
	 * The DOM element.
	 */
	element: null,
	/**
	 * Shim frame for IE6
	 */
	shim: null,
	/**
	 * Create the div to be used.
	 *
	 * @constructor
	 */
	initialize: function() {
		Blackout.element = $('<div/>', {
			id: 'blackout'
		});
		Blackout.element.on('click', Core.stopPropagation).on('keyup', Blackout.listen);
		$("body").append(Blackout.element);
		// IE6 fix
		if (Core.browser.full === 'ie6') {
			Blackout.element.css('backgroundColor', '#000');
			Blackout.element.css('filter', 'alpha(opacity=70)');
			Blackout.shim = $('<iframe />', {
				src: 'javascript:false;',
				frameborder: 0,
				scrolling: 'no'
			}).addClass('support-shim');
			Blackout.element.append(Blackout.shim);
		}
		Blackout.initialized = true;
	},
	/**
	 * Listen for keyboard esc input; if so, close blackout.
	 *
	 * @param e
	 */
	listen: function(e) {
		if (e.which === KeyCode.esc) {
			Blackout.hide();
		}
	},
	/**
	 * Shows the blackout.
	 *
	 * @param callback			Function that gets called after blackout shows
	 * @param onClick			Function binds onclick functionality to blackout
	 * @param transparent		Boolean on whether to make the background transparent (true) or black (false)
	 */
	show: function(callback, onClick, transparent) {
		if (!Blackout.initialized) {
			Blackout.initialize();
		}
		// IE fix
		if (Core.isIE()) {
			Blackout.element.css("width", Page.dimensions.width).css("height", $(
				document).height());
			// IE6 fix
			if (Core.browser.full === 'ie6') {
				Blackout.shim.css('width', Blackout.element.css('width')).css('height',
					Blackout.element.css('height'));
				if (Core.isCallback(onClick)) {
					Blackout.shim[0].contentWindow.document.onclick = onClick;
				}
			}
		}
		if (transparent) {
			Blackout.element.addClass('blackout-transparent');
		}
		// Show blackout
		Blackout.element.show();
		// Call optional functions
		if (Core.isCallback(callback)) {
			callback();
		}
		if (Core.isCallback(onClick)) {
			Blackout.element.click(onClick);
		}
	},
	/**
	 * Hides blackout.
	 *
	 * @param callback		Function that gets called after blackout hides
	 */
	hide: function(callback) {
		Blackout.element.hide();
		if (Core.isCallback(callback)) {
			callback();
		}
		Blackout.element.unbind("click").removeClass('blackout-transparent');
	}
};
/**
 * Object.create() polyfill for older browsers.
 */
if (!Object.create) {
	Object.create = function(o) {
		if (arguments.length > 1) {
			throw new Error(
				'Object.create implementation only accepts the first parameter.');
		}

		function F() {}
		F.prototype = o;
		return new F();
	};
}
/**
 * Object.getPrototypeOf polyfill for older browsers.
 *
 * @see http://ejohn.org/blog/objectgetprototypeof/
 */
if (!Object.getPrototypeOf) {
	if (typeof 'test'.__proto__ === 'object') {
		Object.getPrototypeOf = function(object) {
			return object.__proto__;
		};
	} else {
		Object.getPrototypeOf = function(object) {
			// May break if the constructor has been tampered with
			return object.constructor.prototype;
		};
	}
}
/**
 * Polyfill for String.fromCodePoint() in ES6.
 * Generates strings from Unicode code points. String.fromCharCode() cannot handle high code points and should be deprecated.
 */
if (!String.fromCodePoint) {
	/*!
	 * ES6 Unicode Shims 0.1
	 * (c) 2012 Steven Levithan <http://slevithan.com/>
	 * MIT License
	 */
	String.fromCodePoint = function fromCodePoint() {
		var chars = [],
			point, offset, units, i;
		for (i = 0; i < arguments.length; ++i) {
			point = arguments[i];
			offset = point - 0x10000;
			units = point > 0xFFFF ? [0xD800 + (offset >> 10), 0xDC00 + (offset & 0x3FF)] : [
				point
			];
			chars.push(String.fromCharCode.apply(null, units));
		}
		return chars.join("");
	};
}
/**
 * Prototype extensions.
 */
if (!String.prototype.trim) {
	String.prototype.trim = function() {
		return $.trim(this);
	};
}
if (!String.prototype.capitalize) {
	String.prototype.capitalize = function() {
		return this.charAt(0).toUpperCase() + this.slice(1);
	};
}
/**
 * The following 2 functions will heck if jQuery.expr.createPseudo exist.
 * jQuery.expr.createPseudo was introduced in jQuery 1.8, this check ensures apps using older version
 * of jQuery won't break.
 */
/**
 * caseInsensitiveContains jquery custom pseudoselector
 *
 */
jQuery.expr[":"].caseInsensitiveContains = typeof(jQuery.expr.createPseudo) ==
	"function" ? jQuery.expr.createPseudo(function(arg) {
		return function(elem) {
			return jQuery(elem).text().toLocaleLowerCase().indexOf(arg.toLocaleLowerCase()) >=
				0;
		};
	}) : function(elem, i, match) {
		return jQuery(elem).text().toLocaleLowerCase().indexOf(match[3].toLocaleLowerCase()) >=
			0;
	};
/**
 * caseInsensitiveStartsWith jquery custom pseudoselector
 *
 */
jQuery.expr[":"].caseInsensitiveStartsWith = typeof(jQuery.expr.createPseudo) ==
	"function" ? jQuery.expr.createPseudo(function(searchString) {
		return function(elem) {
			return jQuery(elem).text().toLocaleLowerCase().indexOf(searchString.toLocaleLowerCase()) ===
				0;
		};
	}) : function(elem, i, match) {
		return jQuery(elem).text().toLocaleLowerCase().indexOf(match[3].toLocaleLowerCase()) ===
			0;
	};
/**
 * jQuery extensions.
 */
if (!jQuery.Event.prototype.stop) {
	jQuery.Event.prototype.stop = function() {
		this.preventDefault();
		this.stopPropagation();
	};
}
/**
 * Setup ajax calls.
 */
$.ajaxSetup({
	error: function(xhr) {
		if (xhr.readyState !== 4) {
			return false;
		}
		if (xhr.getResponseHeader("X-App") === "login") {
			Login.openOrRedirect();
			return false;
		}
		if (xhr.status) {
			switch (xhr.status) {
				case 301:
				case 302:
				case 307:
				case 403:
				case 404:
				case 500:
				case 503:
					return false;
					break;
			}
		}
		return true;
	},
	statusCode: {
		403: function() {
			// Via AJAX calls
			Login.openOrRedirect();
		}
	}
});
/**
 * Manage the context / character selection menu.
 */
var CharSelect = {
	/**
	 * Textarea content to persist between switches.
	 */
	textareaContent: '',
	/**
	 * Callback function to be triggered after a pin but before the page content is replaced.
	 */
	beforeCallback: null,
	/**
	 * Callback function to be triggered after the page content was replaced.
	 */
	afterCallback: null,
	/**
	 * Initialize the class.
	 *
	 * @constructor
	 */
	initialize: function() {
		$(document).on('click', 'a.context-link', CharSelect.toggle);
		$('input.character-filter').on({
			blur: function() {
				Toggle.keepOpen = false;
			},
			keydown: function(e) {
				if (e.which === KeyCode.enter) {
					e.stopPropagation();
					e.preventDefault();
				}
			},
			keyup: CharSelect.filter
		});
		Input.bind('.character-filter');
	},
	/**
	 * Pin a character to the top.
	 *
	 * @param index
	 * @param node
	 */
	pin: function(index, node) {
		Tooltip.hide();
		$('div.character-list').html("").addClass('loading-chars');
		$.ajax({
			type: 'POST',
			url: Core.baseUrl + '/account/pin/'+index,
			data: {
				index: index,
				xstoken: Cookie.read('xstoken')
			},
			global: false,
			success: function(response) {
				if (Core.isIE()) {
					location.reload(true);
				}
				// Trigger callback and break early if callback returns true
				if (Core.isCallback(CharSelect.beforeCallback) && CharSelect.beforeCallback(
					node)) {
					return;
				}
				//update comments area first
				CharSelect.updateComments(response);
				// Refresh page elements
				CharSelect.update();
			}
		})
	},
	/**
	 * Update all the elements on the page after char selection.
	 */
	update: function() {
		$.ajax({
			url: location.href,
			cache: false,
			global: false,
			error: function() {
				location.reload(true);
			},
			success: function(response) {
				var pageData = $(typeof response === 'string' ? response : response.documentElement);
				$('.ajax-update').each(function() {
					var self = $(this),
						target;
					if (self.attr('id')) {
						target = '#' + self.attr('id');
					} else {
						target = self.attr('class').replace('ajax-update', '').trim();
						target = '.' + target.split(' ')[0];
					}
					var clone = pageData.find(target + '.ajax-update').clone(),
						textarea = self.find('textarea');
					if (textarea.length && textarea.val().length) {
						CharSelect.textareaContent = textarea.val();
					}
					if (clone.length) {
						self.replaceWith(clone);
					}
				});
				if (Core.isCallback(CharSelect.afterCallback)) {
					CharSelect.afterCallback();
				}
			}
		});
	},
	/**
	 * Update the avatars in the comments after a char switch
	 *
	 * @param response
	 */
	updateComments: function(response) {
		var pageData = $(typeof response === 'string' ? response : response.documentElement);
		var commentsWrapper = $("#comments");
		if (commentsWrapper.length) {
			var commentsForm = $(".comments-form", $("#comments"));
			//update username
			$(".character-info", commentsForm).each(function() {
				var updatedCharacter = pageData.find('.character-info').clone();
				$(this).replaceWith(updatedCharacter);
			});
			//update avatar
			$(".avatar-outer a", commentsForm).each(function() {
				var updatedAvatar = pageData.find('.portrait-b .avatar-interior a').clone();
				$(this).replaceWith(updatedAvatar);
			});
			//update reply form
			if (typeof Comments !== "undefined") {
				//update username
				$(".character-info", Comments.replyForm).each(function() {
					$(this).replaceWith(pageData.find('.character-info').clone());
				});
				//update avatar
				$(".avatar-outer a", Comments.replyForm).each(function() {
					$(this).replaceWith(pageData.find('.portrait-b .avatar-interior a').clone());
				});
			}
		}
	},
	/**
	 * Open and close the context menu.
	 *
	 * @param e
	 */
	toggle: function(e) {
		e.stop();
		Toggle.open(e.currentTarget, 'context-open', $(e.currentTarget).siblings(
			'.ui-context'));
		return false;
	},
	/**
	 * Close the context menu.
	 *
	 * @param node
	 * @return boolean
	 */
	close: function(node) {
		$(node).parents('.ui-context').hide().siblings('.context-link').removeClass(
			'context-open');
		return false;
	},
	/**
	 * Swipe between the char select panes.
	 *
	 * @param direction
	 * @param target
	 */
	swipe: function(direction, target) {
		var parent = $(target).parents('.chars-pane'),
			inDirection = (direction == 'in') ? 'left' : 'right',
			outDirection = (direction == 'in') ? 'right' : 'left';
		parent.hide('slide', {
			direction: inDirection
		}, 150, function() {
			parent.siblings('.chars-pane').show('slide', {
				direction: outDirection
			}, 150, function() {
				var scroll = $(this).find('.scrollbar-wrapper');
				if (scroll.length > 0) {
					scroll.tinyscrollbar();
				}
			});
		});
	},
	/**
	 * Filter down the character list.
	 *
	 * @param e
	 */
	filter: function(e) {
		e.stop();
		Toggle.keepOpen = true;
		if (e.keyCode == KeyCode.enter) {
			return;
		}
		var target = $(e.srcElement || e.currentTarget),
			filterVal = target.val().toLowerCase(),
			filterTable = target.parents('.chars-pane').find('.overview');
		if (e.keyCode == KeyCode.esc) {
			target.val('');
		}
		if (target.val().length < 1) {
			filterTable.children('a').removeClass('filtered');
		} else {
			filterTable.children('a').each(function() {
				$(this)[($(this).text().toLowerCase().indexOf(filterVal) > -1) ?
					"removeClass" : "addClass"]('filtered');
			});
			var allHidden = filterTable.children('a.filtered').length >= filterTable.children(
				'a').length;
			filterTable.children('.no-results')[(allHidden) ? "show" : "hide"]();
		}
		var scroll = target.parents('.chars-pane:first').find('.scrollbar-wrapper');
		if (scroll.length > 0) {
			scroll.tinyscrollbar();
		}
	}
};
$(function() {
	CharSelect.initialize();
});
/**
 * Methods for creating, reading, and deleting cookies.
 */
var Cookie = {
	/**
	 * Cached cookies.
	 */
	cache: {},
	/**
	 * Create a cookie. Can accept a third parameter as a literal object of options.
	 *
	 * @param key
	 * @param value
	 * @param options
	 */
	create: function(key, value, options) {
		options = $.extend({}, options);
		options.expires = options.expires || 1; // Default expiration: 1 hour
		if (typeof options.expires === 'number') {
			var hours = options.expires;
			options.expires = new Date();
			options.expires.setTime(options.expires.getTime() + (hours * 3600000));
		}
		var cookie = [
			encodeURIComponent(key) + '=',
			options.escape ? encodeURIComponent(value) : value,
			options.expires ? '; expires=' + options.expires.toUTCString() : '',
			options.path ? '; path=' + options.path : '',
			options.domain ? '; domain=' + options.domain : '',
			options.secure ? '; secure' : ''
		];
		document.cookie = cookie.join('');
		if (Cookie.cache) {
			if (options.expires.getTime() < (new Date()).getTime()) {
				delete Cookie.cache[key];
			} else {
				Cookie.cache[key] = value;
			}
		}
	},
	/**
	 * Read a cookie.
	 *
	 * @param key
	 * @return string
	 */
	read: function(key) {
		// Use cache when available
		if (Cookie.cache[key]) {
			return Cookie.cache[key];
		}
		var cache = {};
		var cookies = document.cookie.split(';');
		if (cookies.length > 0) {
			for (var i = 0; i < cookies.length; i++) {
				var parts = cookies[i].split('=');
				if (parts.length >= 2) {
					cache[$.trim(parts[0])] = parts[1];
				}
			}
		}
		Cookie.cache = cache;
		return cache[key] || null;
	},
	/**
	 * Delete a cookie.
	 *
	 * @param key
	 */
	erase: function(key, options) {
		if (!options) {
			options = {
				expires: -1
			};
		} else if (!options.expires) {
			options.expires = -1;
		}
		Cookie.create(key, 0, options);
	},
	/**
	 * Returns whether cookies are supported/enabled by the browser
	 *
	 * @return boolean
	 */
	isSupported: function() {
		return (document.cookie.indexOf('=') !== -1);
	}
};
/**
 * Core global functionality.
 */
var Core = {
	/**
	 * Base context URL for the project.
	 */
	baseUrl: '/',
	/**
	 * Battle.net support site URL.
	 */
	supportUrl: '/support/',
	/**
	 * The cached string for the browser.
	 */
	browser: null,
	/**
	 * Dynamic load queue.
	 */
	deferredLoadQueue: [],
	/**
	 * Current locale and region.
	 */
	locale: 'en-us',
	region: 'us',
	/**
	 * Short date format
	 */
	shortDateFormat: 'MM/dd/Y',
	/**
	 * Date/time format
	 */
	dateTimeFormat: 'dd/MM/yyyy HH:mm',
	/**
	 * The current project.
	 */
	project: '',
	/**
	 * Path to static content.
	 */
	staticUrl: '/',
	sharedStaticUrl: '/local-common/',
	/**
	 * The current host and protocol.
	 */
	host: '',
	/**
	 * User agent specification
	 */
	userAgent: 'web',
	/**
	 * Initialize the script.
	 *
	 * @constructor
	 */
	initialize: function() {
		Core.processLoadQueue();
		Core.ui();
		Core.host = location.protocol + '//' + (location.host || location.hostname);
		// Determine the user agent and add the class name to the html tag
		var html = $('html'),
			browser = Core.getBrowser();
		if (browser.name !== '') {
			html.addClass(browser.name).addClass(browser.full);
			if (browser.name === 'ie' && (browser.version === 6 || browser.version ===
				7)) {
				html.addClass('ie67');
			}
		}
	},
	/**
	 * Return letter (alphabet) values only within a string.
	 *
	 * @param string
	 * @return string
	 */
	alpha: function(string) {
		return string.replace(/[^a-zA-Z]/gi, '');
	},
	/**
	 * Create a frame within the document.
	 *
	 * @param url
	 * @param width
	 * @param height
	 * @param parent
	 * @param id
	 */
	appendFrame: function(url, width, height, parent, id) {
		if (typeof url === 'undefined') return;
		if (typeof width === 'undefined') width = 1;
		if (typeof height === 'undefined') height = 1;
		if (typeof parent === 'undefined') parent = $('body');
		if (Core.isIE()) {
			parent.append('<iframe src="' + url + '" width="' + width + '" height="' +
				height + '" scrolling="no" frameborder="0" allowTransparency="true"' + (
					(typeof id !== 'undefined') ? ' id="' + id + '"' : '') + '></iframe>');
		} else {
			parent.append('<object type="text/html" data="' + url + '" width="' +
				width + '" height="' + height + '"' + ((typeof id !== 'undefined') ?
					' id="' + id + '"' : '') + '></object>');
		}
	},
	/**
	 * Escape a string for DOM searching
	 *
	 * @param str
	 * @return string
	 */
	escapeSelector: function(str) {
		return typeof str === "string" ? str.replace(/[^-\w]/g, "\\$&") : str
	},
	/**
	 * Fix column headers if multiple lines.
	 *
	 * @param query
	 * @param baseHeight
	 */
	fixTableHeaders: function(query, baseHeight) {
		$(query).each(function() {
			baseHeight = baseHeight || 18;
			var table = $(this);
			var height = baseHeight;
			table.find('.sort-link').each(function() {
				var linkHeight = $(this).height();
				if (linkHeight > height) {
					height = linkHeight;
				}
			});
			if (height > baseHeight) {
				table.find('.sort-link, .sort-tab').css('height', height);
			}
		});
	},
	/**
	 * Format a locale to a specific structure.
	 *
	 * @param format
	 * @param divider
	 * @return string
	 */
	formatLocale: function(format, divider) {
		divider = divider || '-';
		format = format || 1;
		switch (format) {
			case 1:
			default:
				return Core.locale.replace('-', divider);
				break;
			case 2:
				var parts = Core.locale.split('-');
				return parts[0] + divider + parts[1].toUpperCase();
				break;
			case 3:
				return Core.locale.toUpperCase().replace('-', divider);
				break;
		}
	},
	/**
	 * Convert a datetime string to a users local time zone and return as a string using the specified format.
	 *
	 * http://www.w3.org/TR/html5/common-microsyntaxes.html#valid-global-date-and-time-string
	 *
	 * @param format
	 * @param datetime (2010-07-22T07:41-07:00)
	 * @return string
	 */
	formatDatetime: function(format, datetime) {
		var localDate;
		if (!datetime) {
			localDate = new Date();
		} else {
			// gecko
			localDate = new Date(datetime);
			// webkit
			if (isNaN(localDate.getTime())) { // 2010-07-22 07:41 GMT-0700
				datetime = datetime.substring(0, 10) + ' ' + datetime.substring(11, 16) +
					':00 GMT' + datetime.substring(16, 19) + datetime.substring(20, 22);
				localDate = new Date(datetime);
			}
			// safari still thinking different
			if (isNaN(localDate.getTime())) { // 2010/07/22 07:41 GMT-0700
				datetime = datetime.substring(0, 4) + '/' + datetime.substring(5, 7) +
					'/' + datetime.substring(8, 29);
				localDate = new Date(datetime);
			}
			// trident
			if (isNaN(localDate.getTime())) { // 07-22 07:41 GMT-0700 2010
				datetime = datetime.substring(5, 10) + ' ' + datetime.substring(11, 16) +
					' GMT' + datetime.substring(23, 28) + ' ' + datetime.substring(0, 4);
				localDate = new Date(datetime);
			}
			if (isNaN(localDate.getTime())) {
				return false;
			}
		}
		if (!format) {
			format = 'yyyy-MM-ddThh:mmZ';
		}
		var hr = localDate.getHours(),
			meridiem = 'AM';
		if (hr > 12) {
			hr -= 12;
			meridiem = 'PM'
		} else if (hr === 12) {
			meridiem = 'PM'
		} else if (hr === 0) {
			hr = 12;
		}
		var tz = parseInt(localDate.getTimezoneOffset() / 60 * -1, 10);
		if (tz < 0) {
			tz = '-' + Core.zeroFill(Math.abs(tz), 2) + ':00';
		} else {
			tz = ' + ' + Core.zeroFill(Math.abs(tz), 2) + ':00';
		}
		format = format.replace('yyyy', localDate.getFullYear());
		format = format.replace('MM', Core.zeroFill(localDate.getMonth() + 1, 2));
		format = format.replace('dd', Core.zeroFill(localDate.getDate(), 2));
		format = format.replace('HH', Core.zeroFill(localDate.getHours(), 2));
		format = format.replace('hh', Core.zeroFill(hr, 2));
		format = format.replace('mm', Core.zeroFill(localDate.getMinutes(), 2));
		format = format.replace('a', meridiem);
		format = format.replace('Z', tz);
		return format;
	},
	/**
	 * Detect the browser type, based on feature detection and not user agent.
	 *
	 * @return object
	 */
	getBrowser: function() {
		if (Core.browser) {
			return Core.browser;
		}
		var s = $.support,
			browser = '',
			version = 0;
		if (!s.hrefNormalized && !s.tbody && !s.style && !s.opacity) {
			if (typeof document.body.style.maxHeight !== "undefined" || window.XMLHttpRequest) {
				browser = 'ie';
				version = 7;
			} else {
				browser = 'ie';
				version = 6;
			}
		} else if (s.hrefNormalized && s.tbody && s.style && !s.opacity) {
			browser = 'ie';
			version = 8;
			// $.browser was removed from jQuery in version 1.9
		} else if (typeof $.browser !== 'undefined') {
			if ($.browser.mozilla) {
				browser = 'ff';
			} else if ($.browser.msie) {
				browser = 'ie';
			} else if ($.browser.webkit) {
				if (navigator.userAgent.toLowerCase().indexOf('chrome') >= 0) {
					browser = 'chrome';
				} else {
					browser = 'safari';
				}
			} else if ($.browser.opera) {
				browser = 'opera';
			}
			version = parseInt($.browser.version, 10);
		}
		Core.browser = {
			name: browser,
			full: browser + version,
			version: version
		};
		return Core.browser;
	},
	/**
	 * Get the hash from the URL.
	 *
	 * @return string
	 */
	getHash: function() {
		var hash = location.hash || "";
		return hash.substr(1, hash.length);
	},
	/**
	 * Return the language based off locale.
	 *
	 * @return string
	 */
	getLanguage: function() {
		return Core.locale.split('-')[0];
	},
	/**
	 * Return the region based off locale.
	 *
	 * @return string
	 */
	getRegion: function() {
		return Core.locale.split('-')[1];
	},
	/**
	 * Conveniently jump to a page.
	 *
	 * @param url
	 * @param base
	 */
	goTo: function(url, base) {
		window.location.href = (base ? Core.baseUrl : '') + url;
		if (window.event) window.event.returnValue = false;
	},
	/**
	 * Include a JavaScript file via XHR.
	 *
	 * @param url
	 * @param success
	 * @param cache - defaults to true
	 */
	include: function(url, success, cache) {
		$.ajax({
			url: url,
			dataType: 'script',
			success: success,
			cache: cache !== false
		});
	},
	/**
	 * Checks to see if the argument is a function/callback.
	 *
	 * @param callback
	 * @return boolean
	 */
	isCallback: function(callback) {
		return (callback && typeof callback === 'function');
	},
	/**
	 * Is the browser using IE?
	 *
	 * @param version
	 * @return boolean
	 */
	isIE: function(version) {
		var browser = Core.getBrowser();
		if (version) {
			return (version === browser.version)
		}
		return (browser.name === 'ie');
	},
	/**
	 * Loads either a JavaScript or CSS file, by default deferring the load until after other
	 * content has loaded. The file type is determined by using the file extension.
	 *
	 * @param path
	 * @param deferred - true by default
	 * @param callback
	 */
	load: function(path, deferred, callback) {
		deferred = deferred !== false;
		if (Page.loaded || !deferred) {
			Core.loadDeferred(path, callback);
		} else {
			Core.deferredLoadQueue.push(path);
		}
	},
	/**
	 * Determine which type to load.
	 *
	 * @param path
	 * @param callback
	 */
	loadDeferred: function(path, callback) {
		var queryIndex = path.indexOf("?");
		var extIndex = path.lastIndexOf(".") + 1;
		var ext = path.substring(extIndex, queryIndex === -1 ? path.length :
			queryIndex);
		switch (ext) {
			case 'js':
				Core.loadDeferredScript(path, callback);
				break;
			case 'css':
				Core.loadDeferredStyle(path);
				break;
		}
	},
	/**
	 * Include JS file.
	 *
	 * @param path
	 * @param callback
	 */
	loadDeferredScript: function(path, callback) {
		$.ajax({
			url: path,
			cache: true,
			global: false,
			dataType: 'script',
			success: callback
		});
	},
	/**
	 * Include CSS file; must be done this way because of IE (of course).
	 *
	 * @param path
	 * @param media
	 */
	loadDeferredStyle: function(path, media) {
		$('head').append('<link rel="stylesheet" href="' + path +
			'" type="text/css" media="' + (media || "all") + '" />');
	},
	/**
	 * Replace {0}, {1}, etc. with the passed arguments.
	 *
	 * @param str
	 * @return string
	 */
	msg: function(str) {
		for (var i = 1, len = arguments.length; i < len; ++i) {
			str = str.replace("{" + (i - 1) + "}", arguments[i]);
		}
		return str;
	},
	/**
	 * This version can handle multiple occurences of the same token, but is slower due to the use of a RegExp. Only use if needed.
	 *
	 * @param str
	 * @return string
	 */
	msgAll: function(str) {
		for (var i = 1, len = arguments.length; i < len; ++i) {
			str = str.replace(new RegExp("\\{" + (i - 1) + "\\}", "g"), arguments[i]);
		}
		return str;
	},
	/**
	 * Return numeric values only within a string.
	 *
	 * @param string
	 * @return int
	 */
	numeric: function(string) {
		string = string.replace(/[^0-9]/gi, '');
		if (!string || isNaN(string)) {
			string = 0;
		}
		return string;
	},
	/**
	 * Open the link in a new window.
	 *
	 * @param node
	 * @return false
	 */
	open: function(node) {
		if (node.href) window.open(node.href);
		return false;
	},
	/**
	 * Helper function for event preventDefault.
	 *
	 * @param e
	 */
	preventDefault: function(e) {
		e.preventDefault();
	},
	/**
	 * Run on page load!
	 */
	processLoadQueue: function() {
		if (Core.deferredLoadQueue.length > 0) {
			for (var i = 0, path; path = Core.deferredLoadQueue[i]; i++) {
				Core.load(path);
			}
		}
	},
	/**
	 * Generate a random number between 0 and up to the argument.
	 *
	 * @param no
	 * @return int
	 */
	randomNumber: function(no) {
		return Math.floor(Math.random() * no);
	},
	/**
	 * Scroll to a specific part of the page.
	 *
	 * @param target
	 * @param duration
	 * @param callback
	 */
	scrollTo: function(target, duration, callback) {
		target = $(target);
		if (target.length !== 1) {
			return;
		}
		var win = $(window),
			winTop = win.scrollTop(),
			winBottom = winTop + win.height(),
			top = target.offset().top;
		top -= 15;
		if (top >= winTop && top <= winBottom) {
			return;
		}
		// $.browser was removed from jQuery in version 1.9
		if (typeof $.browser !== 'undefined') {
			$($.browser.webkit ? 'body' : 'html').animate({
				scrollTop: top
			}, duration || 350, callback || null);
		}
	},
	/**
	 * Scroll to a specific part of the page (enough to make sure it's fully visible).
	 *
	 * @param target
	 * @param duration
	 * @param callback
	 */
	scrollToVisible: function(target, duration, callback) {
		target = $(target);
		if (target.length !== 1) {
			return;
		}
		var win = $(window),
			winTop = win.scrollTop(),
			winBottom = winTop + win.height(),
			top = target.offset().top,
			bottom = top + target.height();
		top -= 15;
		bottom += 15;
		if (top >= winTop && bottom <= winBottom) {
			return;
		}
		// $.browser was removed from jQuery in version 1.9
		if (typeof $.browser !== 'undefined') {
			$($.browser.webkit ? 'body' : 'html').animate({
				scrollTop: (top < winTop ? top : bottom - win.height())
			}, duration || 350, callback || null);
		}
	},
	/**
	 * Helper function for event stopPropagation and preventDefault.
	 *
	 * @param e
	 */
	stopEvent: function(e) {
		e.stop();
	},
	/**
	 * Helper function for event stopPropagation.
	 *
	 * @param e
	 */
	stopPropagation: function(e) {
		e.stopPropagation();
	},
	/**
	 * Trims specific characters off the end of a string.
	 *
	 * @param string
	 * @param c
	 * @return string
	 */
	trimChar: function(string, c) {
		if (string.substr(0, 1) === c) string = string.substr(1, (string.length - 1));
		if (string.substr((string.length - 1), string.length) === c) string =
			string.substr(0, (string.length - 1));
		return string;
	},
	/**
	 * Trims specific characters off the right end of a string.
	 *
	 * @param string
	 * @param charlist
	 * @return string
	 */
	trimRight: function(string, charlist) {
		charlist = !charlist ? ' \\s\u00A0' : (charlist + '').replace(
			/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\\$1');
		return (string + '').replace(new RegExp('[' + charlist + ']+$', 'g'), '');
	},
	/**
	 * Apply global functionality to certain UI elements.
	 *
	 * @param context
	 */
	ui: function(context) {
		context = context || document;
		if (Core.isIE(6)) {
			$(context).find('button.ui-button').hover(function() {
				if (!this.disabled || this.className.indexOf('disabled') == -1) {
					$(this).addClass('hover');
				}
			}, function() {
				$(this).removeClass('hover');
			});
		}
		if (Core.project !== 'bam') {
			$(context).find('button.ui-button').click(function(e) {
				var self = $(this);
				var alt = self.attr('data-text');
				if (typeof alt === 'undefined') {
					alt = "";
				}
				if (this.tagName.toLowerCase() === 'button' && alt !== "") {
					if (self.attr('type') === 'submit') {
						e.preventDefault();
						e.stopPropagation();
						self.find('span span').html(alt);
						self.removeClass('hover').addClass('processing').attr('disabled',
							'disabled');
						// Manually submit
						self.parents('form').submit();
					}
				}
				return true;
			});
		}
	},
	/**
	 * Zero-fills a number to the specified length (works on floats and negatives, too).
	 *
	 * @param number
	 * @param width
	 * @param includeDecimal
	 * @return string
	 */
	zeroFill: function(number, width, includeDecimal) {
		if (typeof includeDecimal === 'undefined') {
			includeDecimal = false;
		}
		var result = parseFloat(number),
			negative = false,
			length = width - result.toString().length,
			i = length - 1;
		if (result < 0) {
			result = Math.abs(result);
			negative = true;
			length++;
			i = length - 1;
		}
		if (width > 0) {
			if (result.toString().indexOf('.') > 0) {
				if (!includeDecimal) {
					length += result.toString().split('.')[1].length;
				}
				length++;
				i = length - 1;
			}
			if (i >= 0) {
				do {
					result = '0' + result;
				} while (i--);
			}
		}
		if (negative) {
			return '-' + result;
		}
		return result;
	},
	/**
	 * Fire a Google Analytics event asynchronously.
	 */
	trackEvent: function(eventCategory, eventAction, eventLabel) {
		window._gaq = _gaq || [];
		_gaq.push(['_trackEvent', eventCategory, eventAction, eventLabel]);
	},
	/**
	 * Fire a GA event on click
	 * @param selector jquery element selector
	 * @param eventCategory GA category if data-category not specified, Core.project if neither specified
	 * @param eventAction GA action if data-action not specified, "Click" if neither specified
	 * @param eventLabel GA label if data-label not specified, Core.locale if neither specified
	 */
	bindTrackEvent: function(selector, eventCategory, eventAction, eventLabel) {
		$(selector).on('click.analytics', function() {
			var self = $(this);
			var submitCategory, submitAction, submitLabel;
			// Allow eventCategory to be a callback function evaluated on click
			if (typeof eventCategory == "function") {
				submitCategory = eventCategory.call(this);
			} else {
				submitCategory = eventCategory;
			}
			// Allow eventLabel to be a callback function evaluated on click
			if (typeof eventLabel == "function") {
				submitLabel = eventLabel.call(this);
			} else {
				submitLabel = eventLabel;
			}
			// Allow eventAction to be a callback function evaluated on click
			if (typeof eventAction == "function") {
				var eventActionToken = self.data('action-attachment') ||
					"Unknown Action";
				submitAction = eventAction.call(this, eventActionToken);
			} else {
				submitAction = eventAction;
			}
			submitCategory = self.data('category') || submitCategory || Core.project;
			submitAction = self.data('action') || submitAction || "Click";
			submitLabel = self.data('label') || submitLabel || Core.locale;
			Core.trackEvent(submitCategory, submitAction, submitLabel);
		});
	},
	/**
	 * Utility for boxes that can be closed permanently.
	 * e.g: New Feature Box, BlizzCon Bar
	 *
	 * @param nodeQuery
	 * @param cookieId
	 * @param options - startDate, endDate, cookieParams, fadeIn, trackingCategory, trackingAction, onShow, onHide
	 */
	showUntilClosed: function(nodeQuery, cookieId, options) {
		options = options || {};
		var node = $(nodeQuery),
			COOKIE_NAME = 'bnet.closed.' + cookieId;
		if (!node.length || !Cookie.isSupported() || Cookie.read(COOKIE_NAME)) {
			return false;
		}
		// Date validation
		var now = new Date();
		if (options.startDate) {
			var startDate = new Date(options.startDate);
			if ((startDate - now) > 0) {
				return false;
			}
		}
		if (options.endDate) {
			var endDate = new Date(options.endDate);
			if ((endDate - now) < 0) {
				return false;
			}
		}
		// Show the node
		if (options.fadeIn) {
			node.fadeIn(options.fadeIn, options.onShow);
		} else {
			node.show();
			if (options.onShow) {
				options.onShow();
			}
		}
		// Click events
		var cookieParams = $.extend({
			path: Core.baseUrl,
			expires: 8760
		}, options.cookieParams || {});
		node.delegate('a', 'click', function() {
			var self = $(this),
				trackingLabel = self.data('label'),
				closeButton = (this.rel === 'close');
			if (closeButton) {
				node.hide();
				if (options.onHide) {
					options.onHide();
				}
			}
			if (closeButton || !options.closeButtonOnly) {
				Cookie.create(COOKIE_NAME, 1, cookieParams);
			}
			if (trackingLabel) {
				Marketing.trackImpression(options.trackingCategory || 'Tracking',
					options.trackingAction || 'Click', trackingLabel);
			}
		});
		return true;
	}
};
$(function() {
	Core.initialize();
});
/*
	Feedback Form
*/
var Feedback = {
	form: null,
	// map field IDs to the name attributes sent in request
	ID_MAP: {
		'url': 'feedback-page-url',
		'email': 'feedback-email',
		'subject': 'feedback-subject',
		'body': 'feedback-body'
	},
	overlayInstance: null,
	pageErrorMsg: '',
	pageReferring: '',
	feedbackUrl: '',
	initialize: function() {
		// if we're looking at the fallback version of the page, don't do anything.
		if ($('#feedback-page').length) {
			return;
		}
		this.feedbackUrl = '/' + Core.locale + '/feedback/';
		// assign event handlers to form callers/buttons
		var $feedbackSuggestionLinks = $('.feedback-suggestion-open'),
			$feedbackErrorLinks = $('.feedback-error-open'),
			self = this;
		$feedbackSuggestionLinks.each(function() {
			this.onclick = function() {
				self.open('suggestion');
				return false;
			};
		});
		$feedbackErrorLinks.each(function() {
			this.onclick = function() {
				self.open('bug');
				return false;
			};
		});
		this.overlayInstance = Overlay;
	},
	open: function(type) {
		var self = this;
		self.overlayInstance.open(self.feedbackUrl + 'feedback-form.frag', {
			ajax: true,
			className: 'feedback-overlay',
			bindClose: false
		});
		$('#overlay').unbind('overlayLoaded');
		// custom event 'overlayLoaded' added to Overlay
		$('#overlay').bind('overlayLoaded', function() {
			var overlayWrapper = $(self.overlayInstance.wrapper);
			self.overlayInstance.position(); // Position is calculated wrong initially, possibly due to ajax not being complete
			// Overlay does not give us an option to set position, so override it here
			overlayWrapper.css('position', 'absolute');
			// make sure the overlay isn't positioned offscreen
			if (parseInt(overlayWrapper.css('top'), 10) < 0) {
				overlayWrapper.css('top', 0);
			}
			self.form = document.getElementById('website-feedback');
			var $headline = $('.feedback-wrapper h2'),
				$introText = $('#feedback-intro-message'),
				submitBtn = document.getElementById('feedback-submit'),
				subjectField = document.getElementById('feedback-subject'),
				pageUrlField = document.getElementById('feedback-page-url'),
				pageUrlSystemField = document.getElementById('page-url-system'),
				$bodyLabel = $('.feedback-body-label #body-label-text'),
				bodyField = document.getElementById('feedback-body'),
				charCount = document.getElementById('feedback-body-char-count'),
				$charCount = $(charCount),
				maxCount = bodyField.getAttribute('maxlength');
			// override the X close button in the overlay to use our cancel method
			$('.overlay-close').unbind('click').bind('click', function(e) {
				e.preventDefault();
				self.cancel();
			});
			if (type === 'suggestion') {
				$headline.html(Feedback.titleWebsiteSuggestion);
				$introText.html(Feedback.introFeedback);
			} else {
				$headline.html(Feedback.titleWebsiteFeedback);
				$introText.html(Feedback.introError);
				$bodyLabel.html(Feedback.feedbackError);
			}
			self.form.setAttribute('action', self.feedbackUrl + type);
			// prepopulate Subject field with page error msg if available
			subjectField.value = self.pageErrorMsg;
			// prepopulate Page URL field with referring page URL
			pageUrlField.value = self.pageReferring;
			pageUrlSystemField.value = self.pageReferring;
			// move focus to the overlay
			$(pageUrlField).focus();
			// keep focus in overlay
			$('#blackout').bind('click.feedback', function(e) {
				$(pageUrlField).focus();
			});
			$(pageUrlField).keydown(function(e) {
				if (e.which === 9 && e.shiftKey) {
					e.preventDefault();
					$(cancelBtn).focus();
				}
			});
			submitBtn.onclick = function() {
				if (!self.submit()) {
					return false;
				}
			};
			// textarea character counter
			charCount.parentNode.style.display = 'block';
			charCount.firstChild.nodeValue = maxCount;
			bodyField.onkeyup = function() {
				if (this.value.length > maxCount) {
					$(this).addClass('.feedback-error');
					$charCount.addClass('error');
				} else {
					$charCount.removeClass('error');
				}
				$charCount.html(maxCount - this.value.length);
			};
		});
	},
	submit: function() {
		var submitUrl = this.form.getAttribute('action'),
			emailField = document.getElementById('feedback-email'),
			charCount = document.getElementById('feedback-body-char-count'),
			feedbackBody = document.getElementById('feedback-body'),
			//emailAddress = $.trim(emailField.value),
			$introText = $('#feedback-intro-message'),
			self = this;
		// clear error states
		$('.feedback-error').each(function() {
			var $this = $(this);
			$this.removeClass('feedback-error');
			if ($this.attr('id') !== 'feedback-body-char-count') {
				$this.next('.feedback-error-msg').hide();
			}
			$(charCount).removeClass('error');
		});
		if (feedbackBody.value.length > (feedbackBody.hasAttribute('maxlength') ?
			feedbackBody.getAttribute('maxlength') : 2000)) {
			$(feedbackBody).addClass('feedback-error');
			$(charCount).addClass('error');
		}
		// validate each field with .feedback-required
		var $requiredFields = $('.feedback-required', this.form);
		$requiredFields.each(function() {
			var $el = $(this);
			if (($el.val() === null) || ($.trim($el.val()) === '')) {
				$el.addClass('feedback-error');
				$el.next('.feedback-error-msg').show();
			}
		});
		// return user to form if required fields left empty or any errors
		if ($('.feedback-error').length === 0) {
			var serializedForm = $(self.form).serializeArray();
			if ($.trim(emailField.value) === '') {
				for (var i = 0, iLen = serializedForm.length; i < iLen; i += 1) {
					if (serializedForm[i].name === 'email') {
						serializedForm[i].value = 'no_email_given@blizzard.com';
					}
				}
			}
			$.ajax({
				type: 'POST',
				url: submitUrl,
				data: serializedForm,
				success: function() {
					var successMsg = document.getElementById('feedback-success'),
						closeBtn = document.getElementById('feedback-close');
					self.form.style.display = 'none';
					successMsg.style.display = 'block';
					$introText.hide();
					closeBtn.onclick = function() {
						self.cancel();
					};
				},
				error: function(data) {
					var response;
					try {
						response = JSON.parse(data.responseText);
						if (response) {
							$.each(response.fieldErrors, function(key, val) {
								$('#' + self.ID_MAP[key]).addClass('feedback-error').next(
									'.feedback-error-msg').show();
							});
							return false;
						} else {
							throw "Invalid response";
						}
					} catch (e) {
						var failMsg = document.getElementById('feedback-fail'),
							failCloseBtn = document.getElementById('feedback-fail-close');
						self.form.style.display = 'none';
						failMsg.style.display = 'block';
						failCloseBtn.onclick = function() {
							self.cancel();
						};
						return false;
					}
				}
			});
			return false;
		} else {}
	},
	cancel: function() {
		$('.feedback-wrapper').remove();
		$('#blackout').unbind('click.feedback');
		this.overlayInstance.close();
		this.overlayInstance.cache = {};
		return false;
	}
};
/**
 * Manipulates objects consisting of key/value pairs to apply filtering on specific content.
 * Converts the params into a query string within the hash tag: #key1=value&key2=value
 */
var Filter = {
	/**
	 * Custom parameters to be added to the fragment/hash.
	 */
	query: {},
	/**
	 * Keyup timers.
	 */
	timers: {},
	/**
	 * Extracts the hash into an object of key value pairs.
	 *
	 * @param callback
	 * @constructor
	 */
	initialize: function(callback) {
		var total = 0;
		if (location.hash) {
			var hash = Core.getHash();
			if (hash !== 'reset') {
				var params = hash.split('&'),
					parts;
				for (var i = 0, length = params.length; i < length; ++i) {
					parts = params[i].split('=');
					Filter.query[parts[0]] = decodeURIComponent(parts[1]) || 'null';
					total++;
				}
			}
		}
		Filter.uiSetup(true);
		if (Core.isCallback(callback)) {
			callback(Filter.query, total);
		}
	},
	/**
	 * Add a param to the query.
	 *
	 * @param key
	 * @param value
	 */
	addParam: function(key, value) {
		if (key) {
			if (!value || value === "") {
				Filter.deleteParam(key);
			} else {
				Filter.query[key] = value;
			}
		}
	},
	/**
	 * Get range min/max data upon filtering.
	 *
	 * @param self
	 * @param value
	 * @return obj
	 */
	appendRangeData: function(self, value) {
		var range = {};
		if (typeof self.data('min') !== 'undefined') {
			range = {
				min: parseInt(value, 10),
				max: parseInt(self.siblings('input[data-max]').val(), 10),
				base: self.data('min'),
				type: 'min'
			};
		} else {
			range = {
				min: parseInt(self.siblings('input[data-min]').val(), 10),
				max: parseInt(value, 10),
				base: self.data('max'),
				type: 'max'
			};
		}
		return range;
	},
	/**
	 * Apply the query params to the hash.
	 */
	applyQuery: function() {
		var hash = [];
		if (Filter.query) {
			for (var key in Filter.query) {
				if (Filter.query[key] !== null && Filter.query.hasOwnProperty(key)) {
					hash.push(key + '=' + encodeURIComponent(Filter.query[key]));
				}
			}
		}
		if (hash.length > 0) {
			location.replace('#' + hash.join('&'));
		} else {
			Filter.reset();
		}
	},
	/**
	 * Bind default filter event handlers to all input fields.
	 *
	 * @param target
	 * @param callback
	 */
	bindInputs: function(target, callback) {
		$(target).find('[data-filter]').each(function() {
			var self = $(this),
				data = Filter.extractData(self);
			if (data.field === 'text' || data.field === 'textarea') {
				self.keyup(function() {
					data.value = self.val();
					if (data.filter === 'range') {
						data.range = Filter.appendRangeData(self, data.value);
					}
					Filter.setTimer(data.name, data, callback);
				});
			} else if (data.field === 'a') {
				self.click(function() {
					data.value = self.data('value');
					callback(data);
				});
			} else {
				self.change(function() {
					var value = (typeof self.data('value') !== 'undefined') ? self.data(
						'value') : '';
					if (data.field === 'checkbox') {
						data.value = self.is(':checked') ? (value || 'true') : '';
					} else {
						data.value = value || self.val();
					}
					callback(data);
				});
			}
		});
	},
	/**
	 * Default filter applying callback.
	 *
	 * @param query
	 * @param total
	 */
	defaultApply: function(query, total) {
		if (total > 0) {
			$.each(query, function(key, value) {
				var input = $("#filter-" + key);
				if (!input.length) {
					return;
				}
				if (input.is(':checkbox') && value === 'true') {
					input.prop('checked', true);
				} else {
					input.val(value);
				}
			});
		}
	},
	/**
	 * Delete a param.
	 *
	 * @param key
	 */
	deleteParam: function(key) {
		Filter.query[key] = null;
	},
	/**
	 * Extract relevant data attributes info.
	 *
	 * @param el
	 */
	extractData: function(el) {
		var node = $(el),
			nodeName = node.prop('nodeName').toLowerCase();
		return {
			tag: nodeName,
			node: node,
			name: (typeof node.data('name') !== 'undefined') ? node.data('name') : node
				.attr('id').replace('filter-', ''),
			filter: node.data('filter'),
			column: node.data('column'),
			field: (nodeName === 'input') ? node.attr('type') : nodeName,
			value: ''
		};
	},
	/**
	 * Get a specific param.
	 *
	 * @param key
	 */
	getParam: function(key) {
		return Filter.query[key] || null;
	},
	/**
	 * Reset the class to a default state.
	 */
	reset: function() {
		Filter.query = {};
		Filter.timers = {};
		location.replace('#reset');
	},
	/**
	 * Reset all the input fields in a filter form.
	 *
	 * @param target
	 */
	resetInputs: function(target) {
		if (!target) {
			return;
		}
		$(target).find('input, select, textarea').each(function() {
			var self = $(this),
				value;
			if ((value = self.data('min')) !== 'undefined') {
				self.val(value);
			} else if ((value = self.data('max')) !== 'undefined') {
				self.val(value);
			} else if ((value = self.data('default')) !== 'undefined') {
				self.val(value);
			} else {
				self.val('');
			}
			self.removeClass('active').removeAttr('checked');
			if (this.tagName.toLowerCase() === 'input' && (this.type === 'checkbox' ||
				this.type === 'radio')) {
				this.checked = false;
			}
		});
	},
	/**
	 * Set a timer for a keyup event.
	 *
	 * @param key
	 * @param data
	 * @param callback
	 */
	setTimer: function(key, data, callback) {
		if (Filter.timers[key] !== null) {
			window.clearTimeout(Filter.timers[key]);
			Filter.timers[key] = null;
		}
		Filter.timers[key] = window.setTimeout(function() {
			callback(data);
		}, 350);
	},
	/**
	 * Should resetting apply filter updates.
	 */
	applyReset: false,
	/**
	 * Event for .ui-filter input click.
	 *
	 * @param e
	 */
	uiClick: function(e) {
		var input = $(e.currentTarget || e.target),
			view = input.siblings('.view'),
			reset = input.siblings('.reset');
		if (input.val() !== '') {
			view.hide();
			reset.show();
		} else {
			view.show();
			reset.hide();
		}
	},
	/**
	 * Event for .ui-filter reset.
	 *
	 * @param e
	 */
	uiReset: function(e) {
		var reset = $(e.currentTarget || e.target),
			view = reset.siblings('.view'),
			input = reset.siblings('.input');
		view.show();
		reset.hide();
		input.trigger('reset');
		if (Filter.applyReset) {
			var data = Filter.extractData(input);
			Filter.deleteParam(data.name);
			Filter.applyQuery();
		}
	},
	/**
	 * Setup all the UI input fields.
	 *
	 * @param reset
	 */
	uiSetup: function(reset) {
		var ui = $('.ui-filter');
		if (ui.length) {
			ui.find('.reset').click(Filter.uiReset);
			ui.find('.input').bind({
				keyup: Filter.uiClick,
				focus: Input.activate,
				blue: Input.reset,
				reset: function() {
					$(this).val('').trigger('keyup').trigger('blur');
				}
			});
		}
		Filter.applyReset = reset;
	}
};
/**
 * Variables and functions for flash
 */
var Flash = {
	/**
	 * Video player for this project
	 */
	videoPlayer: '',
	/**
	 * The flash base of the videos for this project
	 */
	videoBase: '',
	/**
	 * Rating image based on locale
	 */
	ratingImage: '',
	/**
	 * Express install location
	 */
	expressInstall: 'expressInstall.swf',
	/**
	 * Required version for Flash player
	 */
	requiredVersion: '10.2.154',
	/**
	 * Store values populated after load
	 */
	initialize: function() {
		//set flash base and rating image
		//Flash.defaultVideoParams.base          = Flash.videoBase;
		Flash.defaultVideoFlashVars.ratingPath = Flash.ratingImage;
		Flash.defaultVideoFlashVars.locale = Core.locale;
		Flash.defaultVideoFlashVars.dateFormat = Core.shortDateFormat;
	},
	/**
	 * Default video params for the video player
	 */
	defaultVideoParams: {
		allowFullScreen: "true",
		bgcolor: "#000000",
		allowScriptAccess: "always",
		wmode: "opaque",
		menu: "false"
	},
	/**
	 * Default flash vars for videos
	 */
	defaultVideoFlashVars: {
		ratingFadeTime: "1",
		ratingShowTime: "4", //min requirement for ESRB
		autoPlay: true
	},
	/**
	 * Get Flash Error
	 *
	 * @returns flash error msgs
	 */
	getFlashError: function() {
		var errorDiv = $("<div id=\"flash-error\" class=\"align-center\" />");
		errorDiv.append("<h3 class=\"subheader\">" + Msg.ui.flashErrorHeader +
			"</h3>" + "<p><a href=\"" + Msg.ui.flashErrorUrl + "\">" + Msg.ui.flashErrorText +
			"</a></p>");
		return errorDiv;
	}
};
$(function() {
	Flash.initialize();
});
/**
 * Used to encode/decode basic numbers into a hash string.
 */
var Hash = {
	/**
	 * Base 64
	 */
	base: 'aZbYcXdWeVfUgThSiRjQkPlOmNnMoLpKqJrIsHtGuFvEwDxCyBzA0123456789+/',
	/**
	 * Delimiter used when grouping multiple batches.
	 */
	delimiter: '!',
	/**
	 * Used to denote an empty character.
	 */
	empty: '.',
	/**
	 * Batch multiple hashes with encode.
	 *
	 * @param data
	 * @return string
	 */
	batch: function(data) {
		var hashes = [];
		for (var i = 0, l = data.length; i < l; i++) {
			hashes.push(Hash.encode(data[i]));
		}
		return Core.trimRight(hashes.join(Hash.delimiter), Hash.delimiter);
	},
	/**
	 * Encode an array into a hash using the base.
	 *
	 * @param data
	 * @return string
	 */
	encode: function(data, useEmpty) {
		var hash = '',
			base = Hash.base,
			empty = Hash.empty;
		for (var i = 0, l = data.length; i < l; i++) {
			if (data[i] !== null) {
				hash += base.charAt(data[i]);
			} else if (useEmpty) {
				hash += empty;
			}
		}
		return Core.trimRight(hash, empty);
	},
	/**
	 * Decode a hash into an array using the base.
	 *
	 * @param data
	 * @return array
	 */
	decode: function(data) {
		var array = [],
			base = Hash.base,
			empty = Hash.empty;
		for (var i = 0, l = data.length, v; i < l; i++) {
			v = data.charAt(i);
			v = (v === empty) ? null : base.indexOf(v);
			array.push(v);
		}
		return array;
	}
};
/**
 * Handles pushing/replacing browser state and falling back to hashbang support for older browsers.
 *
 * @link	https://github.com/balupton/history.js/wiki/Intelligent-State-Handling
 */
var History = {
	/**
	 * History support enabled in the browser.
	 */
	enabled: (window.history.pushState),
	/**
	 * Hashbangs mapping.
	 */
	hashbangs: {},
	/**
	 * Keeps a log of all past custom history events.
	 */
	log: [],
	/**
	 * Custom external callbacks.
	 */
	hashChangeCallback: null,
	popStateCallback: null,
	/**
	 * Bind events to the window. Do not use jQuery as it strips the state property from the event object.
	 */
	initialize: function() {
		window.onhashchange = History.onHashChange;
		window.onpopstate = History.onPopState;
	},
	/**
	 * Lookup an entry in the logs, or return all logs.
	 *
	 * @param index
	 * @return array
	 */
	lookup: function(index) {
		return History.log[index] || History.log;
	},
	/**
	 * Reset the history log.
	 */
	flush: function() {
		History.log = [];
	},
	/**
	 * Is the current hash a hashbang.
	 *
	 * @return boolean
	 */
	isHashbang: function() {
		return (Core.getHash().charAt(0) === '!');
	},
	/**
	 * Push a new state and update the url/hashbang.
	 *
	 * @param state
	 * @param url
	 */
	push: function(state, url) {
		state = History.packageState(state);
		History.log.push([state, url]);
		if (History.enabled) {
			window.history.pushState(state, document.title, url);
		} else {
			History.updateHash(state, url);
		}
	},
	/**
	 * Replace the current state and update the url/hashbang.
	 *
	 * @param state
	 * @param url
	 */
	replace: function(state, url) {
		state = History.packageState(state, true);
		History.log[History.log.length - 1] = [state, url];
		if (History.enabled) {
			window.history.replaceState(state, document.title, url);
		} else {
			History.updateHash(state, url);
		}
	},
	/**
	 * Update the hashbang with the new url.
	 *
	 * @param state
	 * @param url
	 */
	updateHash: function(state, url) {
		if (url.indexOf('#') !== -1) {
			url = url.split('#')[0];
		}
		if (url.charAt(0) !== '/') {
			url = '/' + url;
		}
		url = '!' + url.replace('?', '');
		location.hash = url;
		History.hashbangs[url] = state;
	},
	/**
	 * Package the state object with relevant meta data.
	 *
	 * @param state
	 * @param replacing
	 */
	packageState: function(state, replacing) {
		if (replacing) {
			state.logIndex = History.log.length;
		} else {
			state.logIndex = History.log.length + 1;
		}
		state.replacedState = (replacing === true);
		state.pageTitle = document.title;
		state.currentHash = location.hash;
		state.absoluteUrl = location.href;
		state.isHashbang = !History.enabled;
		return state;
	},
	/**
	 * Trigger the custom hashchange event callback.
	 *
	 * @param e
	 */
	onHashChange: function(e) {
		if (Core.isCallback(History.hashChangeCallback)) {
			var state = History.hashbangs[Core.getHash()] || null;
			History.hashChangeCallback(e, state);
		}
	},
	/**
	 * Trigger the custom popstate event callback.
	 *
	 * @param e
	 */
	onPopState: function(e) {
		if (Core.isCallback(History.popStateCallback)) {
			History.popStateCallback(e);
		}
	}
};
$(function() {
	History.initialize();
});
/**
 * Input field helper. Shows default text on blur and hides on focus.
 */
var Input = {
	/**
	 * Initialize binds for search form.
	 */
	initialize: function() {
		Input.bind('#search-field');
	},
	/**
	 * Bind the events to a target.
	 *
	 * @param target
	 */
	bind: function(target) {
		Input.reset(target);
		var field = $(target);
		field.focus(Input.activate).blur(Input.reset);
		field.closest('form').submit(function() {
			return Input.submit(field);
		});
	},
	/**
	 * Save the current placeholder to the cache and remove.
	 *
	 * @param e
	 */
	activate: function(e) {
		var node = (typeof e === 'string') ? $(e) : $(this);
		if (!node.length) {
			return;
		}
		if (node.val() === node.attr('alt')) {
			node.val("");
		}
		node.addClass("active");
	},
	/**
	 * Display placeholder if value is empty.
	 *
	 * @param e
	 */
	reset: function(e) {
		var node = (typeof e === 'string') ? $(e) : $(this);
		if (!node.length) {
			return;
		}
		if (node.val() === "") {
			node.removeClass("active").val(node.attr('alt'));
		} else if (node.val() !== node.attr('alt')) {
			node.addClass("active")
		}
	},
	/**
	 * Clear field when submitting.
	 *
	 * @param node
	 */
	submit: function(node) {
		node = $(node || this);
		if (node.val() === node.attr('alt')) {
			node.val("");
		}
		return true;
	}
};
$(function() {
	Input.initialize();
});
/**
 * Mappings of keyboard key codes for all supported regions.
 *
 * @link http://unixpapa.com/js/key.html
 */
var KeyCode = {
	/**
	 * Convenience codes.
	 */
	backspace: 8,
	enter: 13,
	esc: 27,
	space: 32,
	tab: 9,
	arrowLeft: 37,
	arrowUp: 38,
	arrowRight: 39,
	arrowDown: 40,
	/**
	 * A map of all key codes.
	 *
	 * Supported: en, es, de, ru, ko (no changes), fr
	 */
	map: {
		global: {
			// 0-9 numbers (48-57) and numpad numbers (96-105)
			numbers: [48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 96, 97, 98, 99, 100, 101,
				102, 103, 104, 105
			],
			// A-Z letters
			letters: [65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80,
				81, 82, 83, 84, 85, 86, 87, 88, 89, 90
			],
			// Backspace, tab, enter, shift, ctrl, alt, caps, esc, num, space, page up, page down, end, home, ins, del
			controls: [8, 9, 13, 16, 17, 18, 20, 27, 33, 32, 34, 35, 36, 45, 46, 144],
			// Function (F keys)
			functions: [112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123],
			// Left, right, up, down, arrows
			arrows: [37, 38, 39, 40],
			// Windows, Mac specific buttons
			os: [17, 91, 92, 93, 219, 224]
		},
		de: {
			letters: [59, 192, 219, 222]
		},
		es: {
			letters: [59, 192]
		},
		ru: {
			letters: [59, 188, 190, 192, 219, 221, 222]
		},
		fr: {
			letters: [191]
		}
	},
	/**
	 * Get all the arrows codes.
	 *
	 * @param lang
	 * @return array
	 */
	arrows: function(lang) {
		return KeyCode.get('arrows', lang);
	},
	/**
	 * Get all the control codes.
	 *
	 * @param lang
	 * @return array
	 */
	controls: function(lang) {
		return KeyCode.get('controls', lang);
	},
	/**
	 * Get all the functions codes.
	 *
	 * @param lang
	 * @return array
	 */
	functions: function(lang) {
		return KeyCode.get('functions', lang);
	},
	/**
	 * Return a key code map.
	 *
	 * @param type
	 * @param lang
	 * @return mixed
	 */
	get: function(type, lang) {
		lang = lang || Core.getLanguage();
		var map = [],
			types = [];
		if (typeof type === 'string') {
			types = [type];
		} else {
			types = type;
		}
		for (var i = 0, l = types.length; i < l; ++i) {
			var t = types[i];
			if (!KeyCode.map.global[t]) {
				continue;
			}
			map = map.concat(KeyCode.map.global[t]);
			if (KeyCode.map[lang] && KeyCode.map[lang][t]) {
				map = map.concat(KeyCode.map[lang][t]);
			}
		}
		return map;
	},
	/**
	 * Validates an input to only accept letters and controls.
	 *
	 * @param e
	 * @param lang
	 * @return bool
	 */
	isAlpha: function(e, lang) {
		return ($.inArray(e.which, KeyCode.get(['letters', 'controls'], lang)) >= 0);
	},
	/**
	 * Validates an input to only accept letters, numbers and controls.
	 *
	 * @param e
	 * @param lang
	 * @return bool
	 */
	isAlnum: function(e, lang) {
		return (KeyCode.isAlpha(e, lang) || KeyCode.isNumeric(e, lang));
	},
	/**
	 * Validates an input to only accept numbers and controls.
	 *
	 * @param e
	 * @param lang
	 * @return bool
	 */
	isNumeric: function(e, lang) {
		return ($.inArray(e.which, KeyCode.get(['numbers', 'controls'], lang)) >= 0) &&
			!e.shiftKey;
	},
	/**
	 * Get all the letter codes.
	 *
	 * @param lang
	 * @return array
	 */
	letters: function(lang) {
		return KeyCode.get('letters', lang);
	},
	/**
	 * Get all the number codes.
	 *
	 * @param lang
	 * @return array
	 */
	numbers: function(lang) {
		return KeyCode.get('numbers', lang);
	}
};
/**
 * Helper functions for switching language / region.
 */
var Locale = {
	/**
	 * Path to the data source.
	 */
	dataPath: '/fragment/i18n.frag',
	/**
	 * Initialize and bind "open menu" links.
	 *
	 * @constructor
	 */
	initialize: function() {
		var path = location.pathname.replace(Core.baseUrl, "");
		path = path + (location.search || '?');
		$('#change-language, #service .service-language a').click(function() {
			return Locale.openMenu('#change-language', encodeURIComponent(path));
		});
	},
	/**

     * Open up the language selection menu at the target location.
     *
     * @param toggler
     * @param path
     */
	openMenu: function(toggler, path) {
		var node = $('#international');
		toggler = $(toggler);
		path = path || '';
		if (node.is(':visible')) {
			node.slideUp();
			toggler.toggleClass('open');
		} else {
			if (node.html() !== "") {
				Locale.display();
				toggler.toggleClass('open');
			} else {
				$.ajax({
					url: Locale.dataPath + '?path=' + path,
					dataType: 'html',
					success: function(data) {
						if (data) {
							node.replaceWith(data);
							toggler.toggleClass('open');
							Locale.display();
						}
					}
				});
			}
		}
		return false;
	},
	/**
	 * Track language events.
	 *
	 * @param eventAction
	 * @param eventLabel
	 */
	trackEvent: function(eventAction, eventLabel) {
		try {
			_gaq.push(['_trackEvent', 'Battle.net Language Change Event', eventAction,
				eventLabel
			]);
		} catch (e) {}
	},
	/**
	 * Display the international menu.
	 */
	display: function() {
		var node = $('#international');
		node.slideDown('fast', function() {
			$(this).css('display', 'block');
		});
		// Opera doesn't animate on scroll down
		// $.browser was removed from jQuery in version 1.9
		if (typeof $.browser === 'undefined' || !$.browser.opera) {
			$('html, body').animate({
				scrollTop: node.offset().top
			}, 1000);
		}
	}
};
$(function() {
	Locale.initialize();
});
/**
 * Opens a login overlay (or redirects to the login server).
 */
var Login = {
		/**
		 * Configuration constants.
		 */
		CONTAINER_ID: "login-embedded",
		FADE_DURATION: "slow",
		/**
		 * Embedded login URL.
		 */
		embeddedUrl: "/login/login.frag",
		/**
		 * Use token to log in behind the scenes.
		 *
		 * @param token
		 */
		success: function(token) {
			$('<div/>', {
				id: 'embedded-loader'
			}).appendTo('body');
			window.setTimeout(function() {
				var delim = window.location.search ? "&" : "?";
				if (window.location.hash.length === 0) {
					window.location = window.location + delim + "ST=" + token;
				} else {
					var url = window.location.href.substring(0, window.location.href.length -
						window.location.hash.length);
					window.location = url + delim + "ST=" + token + "&ST-frag=" + window.location
						.hash.substring(1);
				}
			}, 100);
		},
		/**
		 * Open embedded login. Returns false to cancel default anchor action, or true if it should proceed.
		 *
		 * @param url
		 * @param legacyHref
		 * @param legacyReferrer
		 */
		open: function(url, legacyHref, legacyReferrer) {
			url = url || Login.embeddedUrl;
			//Tracking time spent opening login window
			Login._timeTracker.start();
			// are we appending params or creating a query string?
			var nSep = (url.indexOf('?') === -1) ? '?' : '&';
			if ("https:" === document.location.protocol) {
				url += nSep;
				url += "secureOrigin=true";
				// be smart, flip if necessary
				nSep = '&';
			}
			// Pass on the loc of the opening page to the login fragment
			if (url.indexOf('?loc=') === -1 || url.indexOf("&loc=") === -1) {
				url += nSep + "loc=" + Core.getLanguage();
				nSep = '&';
			}
			if (url.indexOf('?ref=') === -1 || url.indexOf('&ref=') === -1) {
				url += nSep + "ref=" + encodeURIComponent(window.location);
				nSep = '&';
			}
			if (document.getElementById(Login.CONTAINER_ID)) {
				return false;
			}
			// Old browsers have to use the standard login
			if ((typeof postMessage !== "object" && typeof postMessage !== "function") ||
				typeof JSON !== "object") {
				if (legacyHref) {
					var delim = (legacyReferrer.indexOf("?") === -1) ? "?" : "&";
					document.location = legacyHref + encodeURIComponent(legacyReferrer +
						delim + "ST-frag=" + encodeURIComponent(Core.getHash()));
					return false;
				}
				return true;
			}
			var container = $("<div/>", {
				id: Login.CONTAINER_ID
			});
			container.append($("<iframe/>", {
				src: url,
				frameborder: 0
			})).css("visibility", "hidden");
			if (Core.isIE()) {
				$(window).resize(function() {
					var doc = $(document);
					$('#blackout').css({
						width: doc.width(),
						height: doc.height()
					});
				});
			}
			container.appendTo("body");
			Blackout.show(null, function() {
				Blackout.hide();
				Login._close();
			});
			Login._setListener();
			Login._timeoutHandler.activate();
			return false;
		},
		/**
		 * Attempt to open the embedded login, or redirect.
		 *
		 * @param url
		 * @param legacyHref
		 * @param legacyReferrer
		 */
		openOrRedirect: function(url, legacyHref, legacyReferrer) {
			if (Login.open(url, legacyHref, legacyReferrer)) {
				var href = location.href,
					delim = (href.indexOf("?") === -1) ? "?" : "&";
				location.href = href + delim + 'login=true&cr=true';
			}
		},
		/**
		 * Close the login overlay.
		 *
		 * @param removeBlackout
		 */
		_close: function(removeBlackout) {
			Login._removeListener();
			removeBlackout = removeBlackout || false;
			$("#" + Login.CONTAINER_ID).fadeOut(Login.FADE_DURATION, function() {
				$(this).remove();
				if (removeBlackout) {
					Blackout.hide();
				}
			});
			Login._timeoutHandler.deactivate();
		},
		/**
		 * Post message listener. Triggers specific actions.
		 *
		 * @param event
		 */
		_messageListener: function(event) {
			// No need to validate sender; no critical actions take place here
			var data = JSON.parse(event.data);
			switch (data.action) {
				case "onload":
					Login._timeoutHandler.deactivate();
					//Tracking time spent opening login window
					Login._timeTracker.end();
					var node = $('#' + Login.CONTAINER_ID),
						embed = $('#' + Login.CONTAINER_ID + ' iframe');
					node.css('height', data.height);
					embed.css('height', data.height);
					if (data.height > 500) {
						node.css('margin-top', -(data.height / 2));
						node.css('margin-bottom', -(data.height / 2));
					}
					node.css("visibility", "visible");
					break;
				case "success":
					Login._close();
					Login.success(data.loginTicket);
					break;
				case "close":
					Login._close(true);
					break;
				case "redirect":
					window.location = data.url + "?ref=" + encodeURIComponent(window.location);
					break;
			}
		},
		_setListener: function() {
			Login._removeListener();
			Login._listener = function(event) {
				Login._messageListener(event);
			};
			if (typeof addEventListener !== "undefined") {
				addEventListener("message", Login._listener, false);
			} else {
				attachEvent("onmessage", Login._listener); // IE
			}
		},
		_removeListener: function() {
			if (!Login._listener) {
				return;
			}
			if (typeof removeEventListener !== "undefined") {
				removeEventListener("message", Login._listener, false);
			} else {
				detachEvent("onmessage", Login._listener); // IE
			}
		},
		/**
		 * Tracking the time cost of opening Login page.
		 *
		 */
		_timeTracker: {
			startTime: null,
			start: function() {
				this.startTime = new Date().getTime();
			},
			end: function() {
				// Round up to the closest half seconds
				_gaq.push(["_trackEvent", "debug", "login frag load time", "" + Math.round(
					((new Date().getTime()) - this.startTime) / 500) * 500]);
			}
		},
		/**
		 *  Handle the detection of a failed login popup iframe load. Failing sends the user to the full login page
		 *  Ref: crr-741
		 */
		_timeoutHandler: {
			timeoutMs: 5000,
			/* 5 second timeout */
			timeoutVar: null,
			/* stores the window.timeout reference so we can clear it later */
			loaderVar: null,
			/* store a reference to the spinner div */
			activate: function() {
				this.loaderVar = $("<div/>", {
					id: "embedded-loader"
				}).appendTo("body");
				this.timeoutVar = window.setTimeout(function() {
					Login._timeoutHandler.handleTimeout();
				}, this.timeoutMs);
			},
			deactivate: function() {
				this.loaderVar.remove();
				window.clearTimeout(this.timeoutVar);
				this.timeoutVar = null;
			},
			handleTimeout: function() {
				_gaq.push(["_trackEvent", "debug", "login frag timeout", "" + this.timeoutMs]);
				window.location = "/?login";
			}
		}
	}
	/**
	 * Handles analytics and event tracking.
	 */
var Marketing = {
	/**
	 * Bind ad tracking to an element(s).
	 *
	 * @param query
	 * @param category
	 * @param action
	 */
	bindTracking: function(query, category, action) {
		$(query).click(function() {
			var self = $(this);
			try {
				_gaq.push(['_trackEvent',
					self.data('category') || category,
					self.data('action') || action,
					self.data('ad') + ' [' + Core.locale + ']'
				]);
			} catch (e) {}
		});
	},
	/**
	 * Track user activity on the site.
	 *
	 * @param module
	 * @param label
	 */
	trackActivity: function(module, label) {
		try {
			_gaq.push(['_trackEvent', 'Battle.net User Activity',
				module,
				label + ' [' + Core.locale + ']'
			]);
		} catch (e) {}
	},
	/**
	 * Track a loaded Battle.net ad.
	 *
	 * @param id
	 * @param title
	 * @param ref
	 * @param clickEvent
	 */
	trackAd: function(id, title, ref, clickEvent) {
		try {
			ref = ref ? ref + ' - ' : '';
			_gaq.push(['_trackEvent', 'Battle.net Ad Service', (clickEvent) ?
				'Ad Click-Throughs' : 'Ad Impressions', 'Ad ' + encodeURIComponent(
					title.replace(' ', '_')) + ' - ' + ref + Core.locale + ' - ' + id
			]);
		} catch (e) {}
	},
	/**
	 * Track a page impression / view.
	 *
	 * @param category
	 * @param action
	 * @param label
	 */
	trackImpression: function(category, action, label) {
		try {
			_gaq.push(['_trackEvent',
				category,
				action,
				label + ' [' + Core.locale + ']'
			]);
		} catch (e) {}
	}
};
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
		Menu.container.unbind().mouseleave(function() {
			Menu.timer = window.setTimeout(function() {
				Menu.hide();
			}, Menu.config.duration);
		}).mouseenter(function() {
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
		Menu.children[url].children('a:first, span:first').removeClass('opened').end()
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
		options = $.extend({}, {
			set: 'base'
		}, options || {});
		Menu.node = $(node);
		var data = Menu.dataIndex[options.set][path] || null;
		if (data && data.children) {
			if ($('#' + Menu._id(path, options.set)).is(':visible')) {
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
		options = $.extend({}, {
			set: 'base'
		}, options || {});
		if (!Menu.dataIndex[options.set][path]) {
			return;
		}
		if ($('#' + Menu._id(path, options.set)).is(':visible')) {
			return;
		}
		Menu.hide();
		Menu.node = $(node);
		Menu.openTimer = window.setTimeout(function() {
			Menu._display(path, options);
		}, 200);
		Menu.node.unbind('mouseleave mouseenter').mouseleave(function() {
			window.clearTimeout(Menu.openTimer);
			Menu.timer = window.setTimeout(function() {
				Menu.hide();
			}, Menu.config.duration);
		}).mouseenter(function() {
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
				item.addClass('children').append('<span class="child-arrow"></span>');
			}
			li.hover(function() {
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
			}, function() {
				if (data.children) {
					Menu.timers[menu.url] = window.setTimeout(function() {
						Menu.hideChild(menu.url);
					}, Menu.config.duration);
				}
			});
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
				var width = Math.round(nodeWidth / 2) - Math.round(Menu.config.colWidth /
					2);
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
			mapping = {
				html: label.replace(/&/ig, '&amp;'),
				rel: 'np'
			},
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
/**
 * Creates an overlay box (modal) and blacks out the page for focus.
 * Can fetch content from a DOM element or through AJAX.
 */
var Overlay = {
	/**
	 * Cached results from the AJAX responses.
	 */
	cache: {},
	/**
	 * Default configuration.
	 */
	config: {
		ajax: false,
		bindClose: true,
		className: "",
		fadeSpeed: 250,
		blackout: true
	},
	/**
	 * Has the class been initialized?
	 */
	loaded: null,
	/**
	 * DOM object for the overlay.
	 */
	wrapper: null,
	/**
	 * Initialize the class and create the markup.
	 *
	 * @constructor
	 */
	initialize: function() {
		if (Overlay.loaded && Overlay.wrapper) return;
		Overlay.wrapper = $('<div/>', {
			id: 'overlay',
			'class': 'ui-overlay'
		}).appendTo('body').hide();
		$('<a/>').addClass('overlay-close').attr('href', 'javascript:;').click(
			Overlay.close).appendTo(Overlay.wrapper);
		var top = $('<div/>').addClass('overlay-top').appendTo(Overlay.wrapper);
		var bot = $('<div/>').addClass('overlay-bottom').appendTo(top);
		var mid = $('<div/>').addClass('overlay-middle').appendTo(bot);
		Overlay.loaded = true;
	},
	/**
	 * Close the overlay.
	 */
	close: function(speed) {
		speed = !speed ? 10 : (speed || 250);
		$("#blackout").fadeOut(speed);
		Overlay.wrapper.fadeOut(speed, function() {
			Overlay.setContent("");
			Overlay.wrapper.attr('class', 'ui-overlay');
			if (Overlay.wrapper.attr('id') !== 'overlay') {
				Overlay.wrapper.hide();
			}
		});
	},
	/**
	 * Open up an overlay. Fill the content with text, DOM or AJAX.
	 *
	 * @param content
	 * @param config
	 */
	open: function(content, config) {
		Overlay.initialize();
		config = $.extend({}, Overlay.config, config);
		if (config.className) {
			Overlay.wrapper.addClass(config.className);
		}
		if (config.blackout) {
			if (config.bindClose) {
				Blackout.show(null, function() {
					Overlay.close(config.fadeSpeed);
				});
			} else {
				Blackout.show();
			}
		}
		// Content: AJAXs
		if (config.ajax) {
			// Look in cache
			if (Overlay.cache[content]) {
				Overlay.show(Overlay.cache[content]);
				// fire overlayLoaded event (ajaxComplete is nonspecific)
				$('#overlay').trigger('overlayLoaded');
			} else {
				$.ajax({
					type: "GET",
					url: content,
					dataType: "html",
					beforeSend: function() {
						Overlay.reset();
						Overlay.show();
					},
					success: function(data, status) {
						Overlay.cache[content] = data;
						Overlay.setContent(data);
						$('#overlay').trigger('overlayLoaded');
					}
				});
			}
			// Content: DOM
		} else if (content.substr(0, 1) === '#') {
			Overlay.show($(content).html());
			// Content: Text
		} else {
			Overlay.show(content);
		}
	},
	/**
	 * Open up a custom overlay.
	 *
	 * @param element
	 * @param config
	 */
	openCustom: function(element, config) {
		Overlay.wrapper = $(element);
		if (Overlay.wrapper) {
			config = $.extend({}, Overlay.config, config);
			if (config.blackout) {
				if (config.bindClose) {
					Blackout.show(null, function() {
						Overlay.close(config.fadeSpeed);
					});
				} else {
					Blackout.show();
				}
			}
			Overlay.position();
		}
	},
	/**
	 * Position the overlay at specific coodinates.
	 *
	 * @param node
	 */
	position: function(node) {
		node = node || Overlay.wrapper;
		var width = node.outerWidth(false),
			height = node.outerHeight(false),
			x = (Page.dimensions.width / 2) - (width / 2),
			y = (Page.dimensions.height / 2) - (height / 2);
		if (Core.isIE(6)) {
			y = Page.scroll.top + y;
		}
		node.show().css({
			left: x + 'px',
			top: y + 'px',
			position: Core.isIE(6) ? 'absolute' : 'fixed'
		});
	},
	/**
	 * Wipe the overlay and display a loading animation.
	 */
	reset: function() {
		Overlay.wrapper.find('.overlay-middle').html("").addClass('overlay-loading');
	},
	/**
	 * Now display the overlay.
	 *
	 * @param content
	 */
	show: function(content) {
		Overlay.setContent(content);
		Overlay.position();
	},
	/**
	 * Set the content of the overlay.
	 *
	 * @param content
	 */
	setContent: function(content) {
		if (content != null) {
			// for empty content, empty the container so we don't have leftovers such as orphan events
			if (content === '') {
				Overlay.wrapper.find('overlay-middle').empty();
			} else {
				Overlay.wrapper.find('.overlay-middle').html(content);
			}
		}
	}
};
/**
 * Utility to record window scroll / dimensions.
 */
var Page = {
	/**
	 * Window object.
	 */
	object: null,
	/**
	 * Initialized?
	 */
	loaded: false,
	/**
	 * Window dimensions.
	 */
	dimensions: {
		width: 0,
		height: 0
	},
	/**
	 * Window scroll.
	 */
	scroll: {
		top: 0,
		width: 0
	},
	/**
	 * Initialized and grab window properties.
	 *
	 * @constructor
	 */
	initialize: function() {
		if (Page.loaded) {
			return;
		}
		if (!Page.object) {
			Page.object = $(window);
		}
		Page.object.resize(Page.getDimensions).scroll(Page.getScrollValues);
		Page.getScrollValues();
		Page.getDimensions();
		Page.loaded = true;
	},
	/**
	 * Get window scroll values.
	 */
	getScrollValues: function() {
		Page.scroll.top = Page.object.scrollTop();
		Page.scroll.left = Page.object.scrollLeft();
	},
	/**
	 * Get window dimensions.
	 */
	getDimensions: function() {
		Page.dimensions.width = Page.object.width();
		Page.dimensions.height = Page.object.height();
	}
};
$(function() {
	Page.initialize();
});
/**
 * Opens up a quick prompt that accepts an input.
 */
var Prompt = {
	/**
	 * DOM objects.
	 */
	node: null,
	input: null,
	title: null,
	errors: null,
	/**
	 * Is markup created?
	 */
	initialized: false,
	/**
	 * Default rules.
	 */
	defaults: {
		minLength: {
			value: 1
		},
		maxLength: {
			value: 25
		},
		numeric: {
			value: false
		}
	},
	/**
	 * Set of rules to validate against.
	 */
	rules: {},
	/**
	 * Validation callback.
	 */
	callback: null,
	/**
	 * Create the DOM elements.
	 */
	initialize: function() {
		Prompt.node = $('<div/>').addClass('ui-prompt').click(Core.stopPropagation)
			.appendTo('body');
		var inner = $('<form/>').attr('method', 'post').attr('action', '').addClass(
			'prompt-inner').appendTo(Prompt.node).submit(Core.preventDefault).keyup(
			function(e) {
				if (e.which === KeyCode.enter) {
					Prompt.validate();
				}
			});
		Prompt.title = $('<h3/>').addClass('subheader').text('').appendTo(inner);
		Prompt.input = $('<input/>').addClass('input').appendTo(inner).focus(Input.activate)
			.blur(Input.reset);
		Prompt.errors = $('<ul/>').addClass('prompt-errors').hide().appendTo(inner);
		var buttons = $('<div/>').addClass('prompt-buttons').appendTo(inner);
		// IE blows up if you set type via attr()
		$('<button type="button"/>').addClass('ui-button button1').html(
			'<span class="button-left"><span class="button-right">' + Msg.ui.submit +
			'</span></span>').click(Prompt.validate).appendTo(buttons);
		$('<button type="button"/>').addClass('ui-button button1').html(
			'<span class="button-left"><span class="button-right">' + Msg.ui.cancel +
			'</span></span>').click(Prompt.close).appendTo(buttons);
		$(document).bind('keyup.prompt', function(e) {
			if (e.which === KeyCode.esc) {
				Prompt.close();
			}
		});
		Prompt.initialized = true;
	},
	/**
	 * Open the prompt at the target location and set the rules and callback.
	 *
	 * @param title
	 * @param callback
	 * @param rules
	 */
	open: function(title, callback, rules) {
		if (!Prompt.initialized) {
			Prompt.initialize();
		}
		var width = Prompt.node.outerWidth(false),
			height = Prompt.node.outerHeight(false),
			x = (Page.dimensions.width / 2) - (width / 2),
			y = (Page.dimensions.height / 2) - (height / 2) + Page.scroll.top;
		Prompt.rules = $.extend({}, Prompt.defaults, rules || {});
		Prompt.callback = callback;
		Prompt.title.text(title);
		Prompt.input.attr('maxlength', Prompt.rules.maxLength.value);
		Prompt.node.css({
			top: y,
			left: x
		}).show();
		Blackout.show(function() {
			Prompt.input.focus();
		}, Prompt.close);
	},
	/**
	 * Close the prompt and reset.
	 */
	close: function() {
		Prompt.input.val('').trigger('blur');
		Prompt.errors.empty().hide();
		Prompt.node.hide();
		Blackout.hide();
	},
	/**
	 * Validate the rules. If successful, trigger callback.
	 *
	 * @param e
	 */
	validate: function() {
		var input = Prompt.input,
			value = input.val().trim(),
			valid = true,
			errors = [],
			rule,
			i = 0,
			l = 0;
		for (var key in Prompt.rules) {
			rule = Prompt.rules[key];
			valid = true;
			if (!rule) {
				continue;
			}
			if (typeof rule.value === 'function') {
				valid = rule.value(value);
			} else {
				switch (key) {
					case 'minLength':
						if (rule.value && value.length < rule.value) valid = false;
						break;
					case 'maxLength':
						if (rule.value && value.length > rule.value) valid = false;
						break;
					case 'numeric':
						if (rule.value && isNaN(value)) valid = false;
						break;
				}
			}
			if (!valid) {
				errors.push(rule.message || key);
			}
		}
		if (errors.length) {
			Prompt.errors.empty().show();
			for (i = 0, l = errors.length; i < l; i++) {
				$('<li/>').text(errors[i]).appendTo(Prompt.errors);
			}
		} else {
			if (Core.isCallback(Prompt.callback)) {
				Prompt.callback(value);
			}
			Prompt.close();
		}
	}
};
/**
 * Initializes typeahead for all Battle.net sites (game community sites, Marketplace, Support).
 */
var Search = {
	/**
	 * Type ahead instance.
	 */
	ta: null,
	/**
	 * Map types to other types.
	 */
	map: {
		articlekeyword: 'article',
		blog: 'article',
		product: 'product',
		'static': 'static',
		/* quote property name to avoid reserved word issue with compressor */
		friend: 'friend'
	},
	/**
	 * Initialize search type ahead.
	 *
	 * @param url
	 */
	initialize: function(url, searchField) {
		var resultTypes = [];
		if (Core.project === 'wow') {
			resultTypes = ['friend', 'url', 'wowcharacter', 'wowguild', 'wowarenateam',
				'wowitem', 'article', 'static', 'other'
			];
			Search.map.character = 'wowcharacter';
			Search.map.arenateam = 'wowarenateam';
			Search.map.guild = 'wowguild';
			Search.map.wowitem = 'wowitem';
			// @todo - deprecate item type, now called wowitem
			Search.map.item = 'wowitem';
			Search.map.friend = 'wowcharacter';
		} else {
			resultTypes = ['friend', 'url', 'product', 'article', 'static', 'other',
				'kb'
			];
		}
		Search.ta = TypeAhead.factory(searchField || '#search-field', {
			groupResults: true,
			resultTypes: resultTypes,
			ghostQuery: (Core.region === 'us' || Core.region === 'eu' || Core.region ===
				'sea'),
			source: function(term, display) {
				$.ajax({
					url: url,
					data: {
						term: term,
						locale: Core.formatLocale(2, '_'),
						community: Core.project
					},
					cache: true,
					dataType: url.charAt(0) === '/' ? 'json' : 'jsonp',
					success: function(response) {
						var results = [];
						if (response.results) {
							for (var i = 0, result; result = response.results[i]; i++) {
								// Temp fix for escaped strings
								var title = '',
									resultContainer = $('<div/>').html(result.title || result.term);
								if (resultContainer) {
									title = resultContainer.text();
								}
								var data = {
									type: Search.map[result.type] || 'url',
									title: title,
									desc: '',
									url: '',
									icon: ''
								};
								switch (result.type) {
									// @todo - get realmUrlSlug
									case 'character':
										data.desc = result.realmName;
										data.url = Core.baseUrl + '/character/' + result.realmName.replace(
											/[^a-z0-9]/ig, '-').toLowerCase() + '/' + result.term + '/';
										break;
										// @todo - get realmUrlSlug
										// @todo - get team size
									case 'arenateam':
										data.desc = result.realmName;
										data.url = Core.baseUrl + '/arena/' + result.realmName.replace(
												/[^a-z0-9]/ig, '-').toLowerCase() + '/' + result.teamSize +
											'/' + result.term + '/';
										break;
										// @todo - get realmUrlSlug
									case 'guild':
										data.desc = result.realmName;
										data.url = Core.baseUrl + '/guild/' + result.realmName.replace(
											/[^a-z0-9]/ig, '-').toLowerCase() + '/' + result.term + '/';
										break;
									case 'wowitem':
										// @todo - deprecate item type, now called wowitem
									case 'item':
										data.className = 'color-q' + result.rarity;
										data.desc = Core.msg(Msg.cms.ilvl, result.level);
										data.url = Core.baseUrl + '/item/' + result.objectId;
										if (result.context) {
											data.url += "/" + result.context;
										}
										break;
									case 'url':
									case 'static':
									case 'product':
										data.desc = result.desc;
										data.icon = result.icon;
										data.url = result.url;
										break;
									case 'friend':
										data.url = Core.projectUrl + result.url;
										data.icon = Core.projectUrl + result.icon;
										break;
									default:
										data.url = '';
										if (result.url) {
											data.url = $('<div/>').html(result.url).text();
										}
										if (data.title !== result.term) {
											data.desc = result.term || '';
										}
										break;
								}
								results.push(data);
							}
						}
						display(results);
					}
				});
			}
		});
	}
};
/**
 * Simple API for interacting with the browsers local storage.
 */
var Storage = {
	/**
	 * Does browser support local storage?
	 */
	initialized: (window.localStorage),
	/**
	 * Get item from storage.
	 *
	 * @param key
	 * @return mixed
	 */
	get: function(key) {
		if (Storage.initialized && key) {
			return localStorage.getItem(key);
		}
		return null;
	},
	/**
	 * Get all items from storage.
	 *
	 * @param prefix
	 * @return mixed
	 */
	getAll: function(prefix) {
		var items = [];
		if (!Storage.initialized) {
			return items;
		}
		for (var i = 0, l = localStorage.length, k = null; i < l; i++) {
			k = localStorage.key(i);
			if (prefix && k.indexOf(prefix) !== 0) {
				continue;
			}
			items.push({
				key: k,
				value: localStorage[k]
			});
		}
		return items;
	},
	/**
	 * Check if a key exists and has a value.
	 *
	 * @param key
	 */
	has: function(key) {
		return (Storage.get(key) !== null);
	},
	/**
	 * Add/set an item into storage.
	 *
	 * @param key
	 * @param value
	 * @return mixed
	 */
	set: function(key, value) {
		if (Storage.initialized && key) {
			try {
				localStorage.setItem(key, value || '');
			} catch (e) {
				if (e === QUOTA_EXCEEDED_ERR) {
					alert('Local storage quota exceeded, please clear your saved data.');
				}
			}
			return true;
		}
		return false;
	},
	/**
	 * Clear all stored data.
	 */
	clear: function() {
		if (Storage.initialized) localStorage.clear();
	},
	/**
	 * Remove a single item from storage.
	 *
	 * @param key
	 */
	remove: function(key) {
		if (Storage.initialized && key) localStorage.removeItem(key);
	},
	/**
	 * Get the total items stored.
	 *
	 * @param prefix
	 * @return int
	 */
	size: function(prefix) {
		if (prefix) {
			return Storage.getAll(prefix).length;
		}
		return localStorage.length || 0;
	}
};
/**
 * Gets and displays unread support tickets.
 */
var Tickets = {
	/**
	 * HTML elements.
	 */
	tag: null,
	summary: null,
	fragment: null,
	ul: null,
	doc: null,
	/**
	 * Total number of ticket statuses to show.
	 */
	total: 3,
	/**
	 * Enable the enhanced support menu.
	 *
	 * @constructor
	 */
	initialize: function() {
		Tickets.doc = document;
		var doc = Tickets.doc;
		Tickets.tag = doc.getElementById('support-ticket-count');
		Tickets.summary = doc.getElementById('ticket-summary');
		Tickets.fragment = doc.createDocumentFragment();
		Tickets.ul = doc.createElement('ul');
		Tickets.loadStatus();
	},
	/**
	 * Update the service menu.
	 *
	 * @param json
	 */
	updateSummary: function(json) {
		var doc = Tickets.doc;
		Tickets.fragment = doc.createDocumentFragment();
		Tickets.ul = doc.createElement('ul');
		Tickets.summary.innerHTML = '';
		Tickets.fragment.appendChild(Tickets.ul);
		if (json.length < 1) {
			return;
		}
		for (var i = 0, ticket; ticket = json[i]; i++) {
			Tickets.createListItem(ticket, i);
		}
		Tickets.summary.appendChild(Tickets.fragment);
	},
	/**
	 * Creates a status summary for a ticket.
	 *
	 * @param ticket A ticket object.
	 * @param index
	 */
	createListItem: function(ticket, index) {
		if (typeof ticket !== 'object') {
			return;
		}
		var doc = Tickets.doc,
			css = Core.isIE(6) || Core.isIE(7) ? 'className' : 'class',
			msgSupport = Msg.support,
			msg = {
				created: msgSupport.ticketNew,
				status: msgSupport.ticketStatus,
				viewAll: msgSupport.ticketAll,
				OPEN: msgSupport.ticketOpen,
				ANSWERED: msgSupport.ticketAnswered,
				RESOLVED: msgSupport.ticketResolved,
				CANCELED: msgSupport.ticketCanceled,
				ARCHIVED: msgSupport.ticketArchived,
				INFO: msgSupport.ticketInfo
			},
			string = '',
			prefix = '',
			suffix = '',
			icon = null,
			li = null,
			a = null,
			span = null,
			br = null,
			datetime = null,
			test = -1;
		if (ticket.status === 'OPEN') {
			string = msg.created.replace('{0}', Core.region.toUpperCase() + ticket.caseId);
		} else {
			string = msg.status.replace('{0}', Core.region.toUpperCase() + ticket.caseId);
		}
		datetime = doc.createElement('span');
		datetime.setAttribute(css, 'ticket-datetime');
		datetime.appendChild(doc.createTextNode(Tickets.localizeDatetime(ticket.lastUpdate)));
		a = doc.createElement('a');
		a.href = Core.secureSupportUrl + 'ticket/thread/' + ticket.caseId;
		icon = doc.createElement('span'),
			icon.setAttribute(css, 'icon-ticket-status');
		a.appendChild(icon);
		test = string.indexOf('{1}');
		if (test > 0) {
			prefix = string.substring(0, test);
			suffix = string.substr(test + 3, string.length);
			span = doc.createElement('span');
			span.setAttribute(css, 'ticket-' + ticket.status.toLowerCase());
			span.appendChild(doc.createTextNode(msg[ticket.status]));
			a.appendChild(doc.createTextNode(prefix));
			a.appendChild(span);
			a.appendChild(doc.createTextNode(suffix));
		} else {
			a.appendChild(doc.createTextNode(string));
		}
		br = doc.createElement('br');
		a.appendChild(br);
		a.appendChild(datetime);
		li = doc.createElement('li');
		if (index === 0) {
			li.setAttribute(css, 'first-ticket');
		}
		li.appendChild(a);
		Tickets.ul.appendChild(li);
		if (index === this.total) {
			li = doc.createElement('li');
			li.setAttribute(css, 'view-all-tickets');
			a = doc.createElement('a');
			a.href = Core.secureSupportUrl + 'ticket/status';
			a.appendChild(doc.createTextNode(msg['viewAll']));
			li.appendChild(a);
			Tickets.ul.appendChild(li);
		}
	},
	/**
	 * Update the service menu tag with the total number of tickets.
	 *
	 * @param count
	 */
	updateTotal: function(count) {
		count = (typeof count === 'number') ? count : 0;
		var css = (Core.isIE(6) || Core.isIE(7)) ? 'className' : 'class';
		if (count > 0) {
			Tickets.tag.setAttribute(css, 'open-support-tickets');
			Tickets.tag.innerHTML = count;
		} else {
			Tickets.tag.setAttribute(css, 'no-support-tickets');
			Tickets.tag.innerHTML = '';
		}
	},
	/**
	 * Localize the date and time per the user's time zone and locale.
	 *
	 * @param timestamp
	 */
	localizeDatetime: function(timestamp) {
		var format = Core.dateTimeFormat,
			locale = Core.locale,
			datetime = null;
		datetime = Core.formatDatetime(format, timestamp);
		if (!datetime) {
			return '';
		}
		if (locale === 'en-us' || locale === 'es-mx' || locale === 'zh-cn' ||
			locale === 'zh-tw') {
			datetime = datetime.replace('/0', '/');
			if (datetime.substr(0, 1) === '0') {
				datetime = datetime.substr(1);
			}
		}
		if (locale === 'en-us' || locale === 'es-mx') {
			datetime = datetime.replace(' 0', ' ');
		}
		return datetime;
	},
	/**
	 * Load the ticket information through AJAX.
	 */
	loadStatus: function() {
		if (Tickets.summary !== null) {
			$.ajax({
				timeout: 3000,
				url: Core.secureSupportUrl + 'update/json',
				ifModified: true,
				global: false,
				dataType: 'jsonp',
				jsonpCallback: 'getStatus',
				data: {
					supportToken: supportToken
				},
				success: function(json, status) {
					if ("notmodified" !== status) {
						Tickets.updateTotal(json.total);
						Tickets.updateSummary(json.details, json.total);
					}
				}
			});
		}
	}
};
$(window).on("load", function() {
	Tickets.initialize();
});
/**
 * Pop up toasts at the bottom left of the browser, or at a certain location.
 */
var Toast = {
	/**
	 * DOM object.
	 */
	container: null,
	/**
	 * Has the class been initialized?
	 */
	initialized: false,
	/**
	 * Max toasts to display.
	 */
	max: 5,
	/**
	 * Default options.
	 */
	options: {
		timer: 15000,
		autoClose: true,
		onClick: null
	},
	/**
	 * Build the container.
	 *
	 * @constructor
	 */
	initialize: function() {
		Toast.container = $('<div/>').attr('id', 'toast-container').show().appendTo(
			'body');
		Toast.initialized = true;
	},
	/**
	 * Create the toast element.
	 *
	 * @param content
	 * @return object
	 */
	create: function(content) {
		var toast = $('<div/>').addClass('ui-toast');
		$('<div/>').addClass('toast-arrow').appendTo(toast);
		$('<div/>').addClass('toast-top').appendTo(toast);
		$('<div/>').addClass('toast-content').appendTo(toast).html(content);
		$('<div/>').addClass('toast-bot').appendTo(toast);
		$('<a/>').addClass('toast-close').attr('href', 'javascript:;').appendTo(
			toast).click(function(e) {
			e.preventDefault();
			e.stopPropagation();
			if (Toast.options.fadeUse) {
				$(this).parent('.ui-toast').fadeOut('normal', function() {
					$(this).remove();
				});
			} else {
				$(this).parent('.ui-toast').remove();
			}
		});
		// Snapshot height with content before hiding
		toast.appendTo(Toast.container).css({
			height: toast.height()
		}).hide();
		return toast;
	},
	/**
	 * Pop up a toast.
	 *
	 * @param content
	 * @param options	timer, autoClose, onClick
	 */
	show: function(content, options) {
		if (!Toast.initialized) {
			Toast.initialize();
		}
		Toast.truncate();
		var toast = Toast.create(content);
		options = $.extend({}, Toast.options, options);
		if (options.autoClose) {
			window.setTimeout(function() {
				toast.fadeOut('normal', function() {
					toast.remove();
				});
			}, options.timer);
		} else {
			toast.click(function() {
				toast.fadeOut('normal', function() {
					toast.remove();
				});
			}).css('cursor', 'pointer');
		}
		if (Core.isCallback(options.onClick)) {
			toast.click(options.onClick).css('cursor', 'pointer');
		}
		toast.fadeIn();
	},
	/**
	 * Truncate toasts if it exceeds the max limit.
	 */
	truncate: function() {
		var total = Toast.container.find('.ui-toast');
		if (total.length > Toast.max) {
			Toast.container.find('.ui-toast:lt(' + Math.round(total.length - Toast.max) +
				')').fadeOut();
		}
	}
};
/**
 * Simple open/hide toggle system.
 */
var Toggle = {
	/**
	 * Node cache.
	 */
	cache: {},
	/**
	 * Custom defined callback function.
	 */
	callback: null,
	/**
	 * Timeout to close the menu automatically.
	 */
	timeout: 800,
	/**
	 * Determines whether or not to persist menu open.
	 */
	keepOpen: false,
	/**
	 * Opens a menu / dropdown element.
	 *
	 * @param triggerNode
	 * @param activeClass
	 * @param targetPath
	 * @param delay
	 */
	open: function(triggerNode, activeClass, targetPath, delay) {
		if (delay) {
			Toggle.timeout = delay;
		}
		//keep menu open
		Toggle.keepOpen = true;
		var key = Toggle.key(targetPath);
		for (var k in Toggle.cache) {
			if (k !== key) {
				Toggle.close(Toggle.cache[k].trigger, Toggle.cache[k].activeClass, Toggle
					.cache[k].target, 0, true);
			}
		}
		//bind events and cache
		if (!Toggle.cache[key]) {
			//bind events and toggle the class
			$(triggerNode).mouseleave(function() {
				Toggle.keepOpen = false;
				Toggle.close(triggerNode, activeClass, targetPath, Toggle.timeout);
			}).mouseenter(function() {
				Toggle.keepOpen = true;
				window.clearTimeout(Toggle.cache[key].timer);
			});
			//bind events and toggle display of the target
			$(targetPath).mouseleave(function() {
				Toggle.keepOpen = false;
				Toggle.close(triggerNode, activeClass, targetPath, Toggle.timeout);
			}).mouseenter(function() {
				Toggle.keepOpen = true;
				window.clearTimeout(Toggle.cache[key].timer);
			});
			//cache properties
			Toggle.cache[key] = {
				trigger: triggerNode,
				target: targetPath,
				activeClass: activeClass,
				key: key,
				timer: null
			};
		}
		//toggle class/display
		$(triggerNode).toggleClass(activeClass);
		$(targetPath).toggle();
		window.clearTimeout(Toggle.cache[key].timer);
	},
	/**
	 * Close the menu and clear any cached timers.
	 *
	 * @param triggerNode
	 * @param activeClass
	 * @param targetPath
	 * @param delay
	 * @param force
	 */
	close: function(triggerNode, activeClass, targetPath, delay, force) {
		force = (typeof force === 'boolean') ? force : false;
		var key = Toggle.key(targetPath);
		window.clearTimeout(Toggle.cache[key].timer);
		Toggle.cache[key].timer = setTimeout(function() {
			if (Toggle.keepOpen && !force) {
				return;
			}
			$(targetPath).hide();
			$(triggerNode).removeClass(activeClass);
			Toggle.triggerCallback();
		}, delay);
	},
	/**
	 * Generate the key.
	 *
	 * @param targetPath
	 * @return string
	 */
	key: function(targetPath) {
		return (typeof targetPath === 'string') ? targetPath : '#' + $(targetPath).attr(
			'id');
	},
	/*
	 * Trigger a callback if defined
	 */
	triggerCallback: function() {
		if (Core.isCallback(Toggle.callback)) Toggle.callback();
	}
};
/**
 * Dynamically create tooltips, append specific content from different medians, and display at certain positions.
 */
var Tooltip = {
	/**
	 * The current tooltip object and its markup
	 */
	wrapper: null,
	wrapperFrame: null,
	/**
	 * Content within the tooltip
	 */
	contentCell: null,
	/**
	 * Cached results from the AJAX responses
	 */
	cache: {},
	/**
	 * Is the mouse currently hovering over the node?
	 */
	currentNode: null,
	/**
	 * Is the tooltip visible?
	 */
	visible: false,
	/**
	 * Default options
	 */
	options: {},
	/**
	 * DOM query.
	 */
	query: null,
	/**
	 * Max tooltip width for IE6.
	 */
	maxWidth: 250,
	/**
	 * Amount in pixels to check around the viewport edge.
	 */
	threshold: 100,
	/**
	 * Is the instance initialized? Else its being called statically.
	 */
	initialized: false,
	/**
	 * Collection of all factory'd instances.
	 */
	instances: {},
	/**
	 * Factory method for generating Tooltip instances.
	 *
	 * @param query
	 * @param options
	 */
	factory: function(query, options) {
		var instance = Object.create(Tooltip);
		instance.init(query, options);
		Tooltip.instances[options.key || query] = instance;
		return instance;
	},
	/**
	 * Initialize the tooltip markup and append it to document.
	 *
	 * @constructor
	 * @param query
	 * @param options
	 */
	init: function(query, options) {
		this.query = query;
		this.options = $.extend({}, {
			ajax: false,
			mode: 'hover',
			className: '',
			location: 'topRight',
			useTable: false,
			showLoading: true,
			onShow: null,
			onPosition: null,
			context: null,
			margin: 10
		}, options);
		//temporary fix for sc2 project to use tables in their tooltip
		if (Core.project === 'sc2') {
			this.options.useTable = true;
		}
		options = this.options;
		// Create markup
		var wrapper = $('<div/>').addClass('ui-tooltip').appendTo("body").hide();
		if (Core.isIE(6)) {
			this.wrapperFrame = $('<iframe/>', {
				src: 'javascript:"";',
				frameborder: 0,
				scrolling: 'no',
				marginwidth: 0,
				marginheight: 0
			}).addClass('tooltip-frame').appendTo('body');
		}
		if (!options.useTable) {
			this.contentCell = $('<div/>').addClass('tooltip-content').appendTo(
				wrapper);
		} else {
			var tooltipTable = $('<table/>', {
				cellspacing: 0,
				cellpadding: 0
			}).appendTo(wrapper);
			var emptyCell = $('<td/>').attr("valign", "top").text(" "),
				emptyRow = $('<tr/>'),
				contentCell = emptyCell.clone();
			tooltipTable.append(emptyRow.clone().append(emptyCell.clone().addClass(
				"top-left")).append(emptyCell.clone().addClass("top-center")).append(
				emptyCell.clone().addClass("top-right"))).append(emptyRow.clone().append(
				emptyCell.clone().addClass("middle-left")).append(contentCell.addClass(
				"middle-center")).append(emptyCell.clone().addClass("middle-right"))).append(
				emptyRow.clone().append(emptyCell.clone().addClass("bottom-left")).append(
					emptyCell.clone().addClass("bottom-center")).append(emptyCell.clone().addClass(
					"bottom-right")));
			this.contentCell = contentCell;
		}
		// Assign to reference later
		this.wrapper = wrapper;
		// Setup events
		var event = (options.mode === 'click') ? 'click' : 'mouseover',
			callback = (typeof options.onShow === 'function') ? options.onShow :
			function(e) {
				var node = $(e.currentTarget),
					content = node.data('tooltip') || node.prop('title');
				if (event === 'click' && this.visible) {
					this.hide();
					return;
				}
				if (content && node.attr('rel') !== 'np') {
					this.show(node, content, node.data('tooltip-options') || this.options);
				}
			};
		$(options.context || document).off(event + '.tooltip', query, $.proxy(
			callback, this)).on(event + '.tooltip', query, $.proxy(callback, this));
		this.initialized = true;
	},
	/**
	 * Grab the content for the tooltip, then pass it on to be positioned.
	 *
	 * @param node
	 * @param content
	 * @param options
	 */
	show: function(node, content, options) {
		// This is an instance so use the common tooltip
		if (!this.initialized) {
			if (Core.userAgent != "mobile") {
				Tooltip.instances.common.show(node, content, options);
			}
			return;
		}
		if (options === true) {
			options = {
				ajax: true
			};
		}
		options = $.extend({}, this.options, options || {});
		this.currentNode = node = $(node);
		// Update trigger node
		if (options.mode !== 'click') {
			node.unbind('mouseout.tooltip').bind('mouseout.tooltip', $.proxy(function() {
				this.hide();
				this.wrapper.removeClass(options.location).removeClass(options.className);
			}, this));
		}
		// Update values
		if (!this['_' + options.location]) {
			options.location = 'topRight';
		}
		// Left align tooltips in the right half of the screen
		if (options.location === this.options.location && node.offset().left > $(
			window).width() / 2) {
			options.location = 'topLeft';
		}
		// Add the location as a classname
		this.wrapper.addClass(options.location);
		if (options.className) {
			this.wrapper.addClass(options.className);
		}
		// Content: DOM node created w/ jQuery
		if (typeof content === 'object') {
			this._position(node, content, options);
		} else if (typeof content === 'string') {
			// Content: AJAX
			if (options.ajax) {
				if (this.cache[content]) {
					this._position(node, this.cache[content], options);
				} else {
					var url = content;
					// Add base URL when provided URL doesn't begin with project URL (e.g. /d3)
					if (url.indexOf(Core.projectUrl) !== 0) {
						url = Core.baseUrl + content;
					}
					if (options.showLoading) {
						window.setTimeout($.proxy(function() {
							if (!this.visible && !this.cache[content]) {
								this._position(node, Msg.ui.loading, options);
							}
						}, this), 500);
					}
					$.ajax({
						type: "GET",
						url: url,
						dataType: "html",
						global: false,
						success: $.proxy(function(data) {
							this.cache[content] = data;
							if (this.currentNode === node) {
								this._position(node, data, options);
							}
						}, this),
						error: $.proxy(function(xhr) {
							if (xhr.status !== 200) {
								this.hide();
							}
						}, this)
					});
				}
				// Content: Copy content from the specified DOM node (referenced by ID)
			} else if (content.substr(0, 1) === '#') {
				this._position(node, $(content).html(), options);
				// Content: Text
			} else {
				this._position(node, content, options);
			}
		}
	},
	/**
	 * Hide the tooltip.
	 */
	hide: function() {
		// This is an instance
		if (this.initialized) {
			if (Core.isIE(6)) {
				this.wrapperFrame.hide();
				this.wrapper.removeAttr('style');
			}
			this.wrapper.hide().unbind('mousemove.tooltip').removeClass(this.options.location)
				.removeClass(this.options.className);
			this.currentNode = null;
			this.visible = false;
			// This is being called statically
			// So hide all instances
		} else {
			for (var key in Tooltip.instances) {
				Tooltip.instances[key].hide();
			}
		}
	},
	/**
	 * Move the tooltip around.
	 *
	 * @param x
	 * @param y
	 * @param w
	 * @param h
	 */
	move: function(x, y, w, h) {
		this.wrapper.css("left", x + "px").css("top", y + "px").show();
		this.visible = true;
		if (Core.isIE(6)) {
			this.wrapperFrame.css({
				width: w + 60,
				height: h,
				left: (x - 60) + "px",
				top: y + "px"
			}).fadeTo(0, 0).show();
			this.wrapper.css('width', w);
		}
	},
	/**
	 * Position the tooltip at specific coordinates.
	 *
	 * @param node
	 * @param content
	 * @param options
	 */
	_position: function(node, content, options) {
		if (typeof options.onPosition === 'function') {
			content = options.onPosition(content);
		}
		if (typeof content === 'string') {
			this.contentCell.html(content);
		} else {
			this.contentCell.empty().append(content);
		}
		var width = this.wrapper.outerWidth(false),
			height = this.wrapper.outerHeight(false);
		if (Core.isIE(6) && width > Tooltip.maxWidth) {
			width = Tooltip.maxWidth;
		}
		var coords = this['_' + options.location](width, height, node);
		if (coords) {
			this.move(coords.x, coords.y, width, height);
		}
	},
	/**
	 * Position at the mouse cursor.
	 *
	 * @param width
	 * @param height
	 * @param node
	 */
	_mouse: function(width, height, node) {
		node.unbind('mousemove.tooltip').bind('mousemove.tooltip', $.proxy(function(
			e) {
			var margin = this.options.margin;
			this.move((e.pageX + margin), (e.pageY + margin), width, height);
		}, this));
	},
	/**
	 * Position at the top left.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_topLeft: function(width, height, node) {
		var offset = node.offset(),
			x = offset.left - width,
			y = offset.top - height;
		return this._checkViewport(x, y, width, height, node);
	},
	/**
	 * Position at the top center.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_topCenter: function(width, height, node) {
		var offset = node.offset(),
			nodeWidth = node.outerWidth(false),
			x = offset.left + ((nodeWidth / 2) - (width / 2)),
			y = offset.top - height - 5;
		return this._checkViewport(x, y, width, height, node);
	},
	/**
	 * Position at the top right.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_topRight: function(width, height, node) {
		var offset = node.offset(),
			nodeWidth = node.outerWidth(false),
			x = offset.left + nodeWidth,
			y = offset.top - height;
		return this._checkViewport(x, y, width, height, node);
	},
	/**
	 * Position at the middle left.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_middleLeft: function(width, height, node) {
		var offset = node.offset(),
			nodeHeight = node.outerHeight(false),
			x = offset.left - width,
			y = (offset.top + (nodeHeight / 2)) - (height / 2);
		return this._checkViewport(x, y, width, height, node);
	},
	/**
	 * Position at the middle right.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_middleRight: function(width, height, node) {
		var offset = node.offset(),
			nodeWidth = node.outerWidth(false),
			nodeHeight = node.outerHeight(false),
			x = offset.left + nodeWidth,
			y = (offset.top + (nodeHeight / 2)) - (height / 2);
		return this._checkViewport(x, y, width, height, node);
	},
	/**
	 * Position at the bottom left.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_bottomLeft: function(width, height, node) {
		var offset = node.offset(),
			nodeHeight = node.outerHeight(false),
			x = offset.left - width,
			y = offset.top + nodeHeight;
		return this._checkViewport(x, y, width, height, node);
	},
	/**
	 * Position at the bottom center.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_bottomCenter: function(width, height, node) {
		var offset = node.offset(),
			nodeWidth = node.outerWidth(false),
			nodeHeight = node.outerHeight(false),
			x = offset.left + ((nodeWidth / 2) - (width / 2)),
			y = offset.top + nodeHeight + 5;
		return this._checkViewport(x, y, width, height, node);
	},
	/**
	 * Position at the bottom right.
	 *
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_bottomRight: function(width, height, node) {
		var offset = node.offset(),
			nodeWidth = node.outerWidth(false),
			nodeHeight = node.outerHeight(false),
			x = offset.left + nodeWidth,
			y = offset.top + nodeHeight;
		return this._checkViewport(x, y, width, height, node);
	},
	/**
	 * Makes sure the tooltip appears within the viewport.
	 *
	 * @param x
	 * @param y
	 * @param width
	 * @param height
	 * @param node
	 * @return object
	 */
	_checkViewport: function(x, y, width, height, node) {
		var offset = node.offset(),
			scroll = Page.scroll,
			dims = Page.dimensions,
			margin = this.options.margin;
		// Greater than x viewport
		if ((x + width) > dims.width) {
			x = dims.width - width;
		}
		// Less than x viewport
		if (x < 0) {
			x = margin;
		}
		// Greater than y viewport
		if ((y + height) > (scroll.top + dims.height)) {
			y = y - ((y + height) - (scroll.top + dims.height));
			// Node on top of viewport scroll
		} else if ((offset.top - Tooltip.threshold) < scroll.top) {
			y = offset.top + node.outerHeight(false);
			// Less than y viewport scrolled
		} else if (y < scroll.top) {
			y = scroll.top + margin;
		}
		// Less than y viewport
		if (y < 0) {
			y = margin;
		}
		return {
			x: x,
			y: y
		};
	}
};
$(function() {
	// Don't bind tooltips on mobile
	if (Core.userAgent != "mobile") {
		Tooltip.factory('[data-tooltip]', {
			key: 'common'
		});
	}
});
/**
 * Displays a dropdown list of values related to the keyword typed in the target input.
 * The values can be aggregated statically or via AJAX.
 *
 * Initialize using TypeAhead.factory();
 */
var TypeAhead = {
	/**
	 * Input field DOM object.
	 */
	node: null,
	/**
	 * Type ahead results listing DOM object.
	 */
	html: null,
	/**
	 * Throttler timer.
	 */
	timer: null,
	/**
	 * Currently searched keyword.
	 */
	term: '',
	/**
	 * Current anchor position when using arrows.
	 */
	position: -1,
	/**
	 * List of result items.
	 */
	items: [],
	cache: [],
	/**
	 * Configuration.
	 */
	config: {},
	/**
	 * Factory method for generating TypeAhead instances.
	 *
	 * @param node
	 * @param config
	 */
	factory: function(node, config) {
		var instance = Object.create(TypeAhead);
		instance.init(node, config);
		return instance;
	},
	/**
	 * Initialize on an input field.
	 *
	 * @param node
	 * @param config
	 */
	init: function(node, config) {
		this.node = $(node);
		if (!this.node.length) {
			return false;
		}
		this.config = $.extend({}, {
			minLength: 3,
			resultsLength: 15,
			groupResults: false,
			termMatch: false,
			resultTypes: [],
			ghostQuery: false,
			useSearchList: false,
			selectKey: KeyCode.enter,
			throttle: 200,
			source: null,
			onSelect: function(item, ta) {
				ta.node.val(item.title || '');
				ta.close();
			},
			onStart: null,
			onFinish: null
		}, config || {});
		if (this.config.minLength <= 0) {
			this.config.minLength = 1;
		}
		this.config.resultTypes.push('default');
		if (this.node.prop('nodeName').toLowerCase() === 'input') {
			this.node.bind('keyup.ta', $.proxy(this.listen, this));
			this.node.bind('keydown.ta', $.proxy(this.trigger, this));
			this.html = $('<div/>').addClass('ui-typeahead').html(Msg.ui.loading).hide()
				.appendTo('body');
			if (this.config.ghostQuery) {
				this.ghost = $('<input/>').attr({
					type: 'text',
					value: '',
					autocomplete: 'off',
					readonly: 'readonly',
					'class': this.node.attr('class')
				}).addClass('input input-ghost').focus(function() {
					// IE compatibility
					$(node).focus();
				});
				this.node.wrap($('<div/>').addClass('ui-typeahead-ghost')).before(this.ghost);
			}
			$(document).bind('click.ta', $.proxy(this.close, this));
			this.node.bind('click.ta', Core.stopPropagation);
			this.html.bind('click.ta', Core.stopPropagation);
		}
	},
	/**
	 * Close the type ahead and reset.
	 */
	close: function() {
		this.position = -1;
		this.html.hide();
		this._setGhostText();
	},
	/**
	 * Render the results.
	 *
	 * @param items
	 */
	finish: function(items) {
		var config = this.config;
		this.items = items || [];
		this.cache = [];
		this._populate();
		this._position();
		if (Core.isCallback(config.onFinish)) {
			config.onFinish(this.term, this);
		}
	},
	/**
	 * Highlight the term within the string.
	 *
	 * @param string
	 * @return string
	 */
	highlight: function(label, string) {
		if (!string) {
			string = label;
		}
		var regexs = this.term.replace(/[-[\]{}()*+?.,\\^$|#]/g, "\\$&").split(' ');
		for (var i = 0, regex; regex = regexs[i]; i++) {
			var l = string.toLowerCase().indexOf(regex.toLowerCase());
			var r = regex.length;
			string = label.substr(0, l) + '<em>' + label.substr(l, r) + '</em>' +
				label.substr(l + r);
		}
		return string;
	},
	/**
	 * Reset ghost text on final keyup event.
	 */
	listen: function() {
		this.term = this.node.val();
		if (this.term.length <= 0) {
			this.close();
		} else {
			this._checkGhostText();
		}
	},
	/**
	 * Begin searching the type ahead system and return results.
	 */
	start: function() {
		var config = this.config,
			type = $.type(config.source);
		if (Core.isCallback(config.onStart)) {
			config.onStart(this.term, this);
		}
		if (type === 'function') {
			config.source(this.term, $.proxy(this.finish, this));
		} else if (type === 'array') {
			this.finish(config.source);
		}
	},
	/**
	 * Set a throttle timer for the keyup event.
	 */
	throttle: function() {
		if (this.timer !== null) {
			window.clearTimeout(this.timer);
			this.timer = null;
		}
		this.timer = window.setTimeout($.proxy(this.start, this), this.config.throttle);
	},
	/**
	 * Detects the term input and applies ghost text. Secondly detects arrow/tab/select events and triggers the appropriate action.
	 *
	 * @param e
	 */
	trigger: function(e) {
		var code = e.which,
			config = this.config,
			value = this.node.val();
		// Apply ghost text
		this._checkGhostText();
		if (!(code === KeyCode.arrowUp || code === KeyCode.arrowDown || code ===
			config.selectKey || code === KeyCode.tab)) {
			// Grab term and fetch results
			if ((value.length + 1) >= this.config.minLength) {
				this.term = value.trim();
				this.throttle();
			} else if (value.length <= 0) {
				this.close();
			}
			return;
		}
		// Default behavior if submitting with enter
		if (code === KeyCode.enter && (this.html.is(':hidden') || this.position ===
			-1)) {
			this.node.parentsUntil('form').parent().submit();
		} else {
			e.preventDefault();
			e.stopPropagation();
		}
		var cache = this.cache,
			items = this.html.find('a');
		items.removeClass('item-active');
		this._setGhostText();
		// Go to item
		if (code === config.selectKey) {
			if (this.position != -1 && cache[this.position]) {
				var item = cache[this.position];
				if (Core.isCallback(config.onSelect)) {
					config.onSelect(item, this);
				} else if (item.url) {
					Core.goTo(item.url);
				}
			}
			// Up, down, tab
		} else {
			if (code === KeyCode.arrowDown) {
				this.position++;
				if (this.position >= items.length) {
					this.position = 0;
				}
			} else if (code === KeyCode.arrowUp) {
				this.position--;
				if (this.position <= -1) {
					this.position = items.length - 1;
				}
			} else if (code === KeyCode.tab) {
				this.position = 0;
			}
			if (cache[this.position]) {
				items.eq(this.position).addClass('item-active');
				this.node.val(cache[this.position].title);
			}
		}
	},
	/**
	 * Position the list beneath the input field.
	 */
	_position: function() {
		if (!this.items.length) {
			this.close();
			return;
		}
		var offset = this.node.offset();
		this.html.css({
			width: this.node.outerWidth(false),
			left: offset.left,
			top: offset.top + this.node.outerHeight(false)
		}).show();
	},
	/**
	 * Populate the list with matched items.
	 */
	_populate: function() {
		if (!this.items.length) {
			return;
		}
		this.html.empty();
		this.position = -1;
		var config = this.config,
			results = this.items,
			term = this.term,
			groups = {};
		if (config.groupResults) {
			for (var i = 0, result; result = results[i]; i++) {
				if (!groups[result.type]) groups[result.type] = [];
				groups[result.type].push(result);
			}
		} else {
			groups['default'] = results;
		}
		var div, ul, a, title,
			counter = 0;
		for (var i = 0, type; type = config.resultTypes[i]; i++) {
			if (counter >= config.resultsLength) break;
			if (groups[type]) {
				var hasItems = false;
				ul = $('<ul/>');
				for (var x = 0, item; item = groups[type][x]; x++) {
					var searchTerm = this.config.useSearchList ? item.searchTerm : item.title;
					if (counter >= config.resultsLength) {
						break;
					} else if (config.termMatch && searchTerm.toLowerCase().indexOf(term.toLowerCase()) <=
						-1) {
						continue;
					}
					title = '<span class="title">' + this.highlight($('<div/>').text(item.title)
						.html(), searchTerm) + '</span>';
					if (item.desc) {
						title += ' <span class="desc">' + $('<div/>').text(item.desc).html() +
							'</span>';
					}
					a = $('<a/>', {
						href: item.url,
						html: title,
						'class': item.className || ''
					});
					if (item.icon || type === 'friend') {
						var img = $('<img/>', {
							src: item.icon,
							width: (type === 'product') ? 100 : 38,
							height: (type === 'product') ? 50 : 38,
							'class': 'icon' + ((type === 'friend') ? ' icon-frame' : '')
						});
						a.html($('<div class="icon-desc"/>').append(a.html()));
						a.prepend(img);
						a.append($('<span class="clear"/>'));
					}
					$('<li/>').append(a).appendTo(ul);
					counter++;
					hasItems = true;
					this.cache.push(item);
				}
				if (hasItems) {
					div = $('<div/>').addClass('group-list group-' + type);
					if (Msg.search && Msg.search[type]) {
						$('<span/>').addClass('group-title').html(Msg.search[type]).appendTo(
							div);
					}
					div.append(ul).appendTo(this.html);
				}
			}
		}
		if (counter) {
			this._checkGhostText();
		}
	},
	/**
	 * Apply the text to the ghost input.
	 *
	 * @param text
	 */
	_setGhostText: function(text) {
		if (this.ghost) {
			this.ghost.val(text || '');
		}
	},
	/**
	 * Check if the first cache result works as ghost text, if so set it.
	 */
	_checkGhostText: function() {
		if (this.ghost) {
			if (this.cache[0]) {
				var text = this.cache[0].title.trim(),
					term = this.node.val().trim();
				if (text.toLowerCase().indexOf(term.toLowerCase()) === 0) {
					//text = text.replace(new RegExp(term, 'i'), term);
					text = term + text.substr(term.length, (text.length - term.length));
				} else {
					text = '';
				}
				this._setGhostText(text);
			} else {
				this._setGhostText();
			}
		}
	}
};
/**
 * Functionality for loading, paging, filtering, pulling, commenting, reporting, etc of discussion comments.
 */
var Comments = {
	/**
	 * The discussion key.
	 */
	key: null,
	/**
	 * The discussion sig.
	 */
	sig: null,
	/**
	 * The total comments after the last pull.
	 */
	count: 0,
	/**
	 * DOM object for the comments wrapper.
	 */
	wrapper: null,
	/**
	 * DOM object for reply form.
	 */
	replyForm: null,
	/**
	 * DOM object for reply textarea.
	 */
	replyInput: null,
	/**
	 * The currently opened comment ID for a reply.
	 */
	replyId: null,
	/**
	 * The collection to post child comments to a parent.
	 */
	collection: null,
	/**
	 * Interval timer for throttling.
	 */
	throttleTimer: null,
	/**
	 * Default comment sorting order.
	 */
	defaultCommentSort: 'best',
	/**
	 * Name of the cookie storing discussion sort preference.
	 */
	commentSortCookie: 'discussion.sort',
	/**
	 * Initialize comments by storing the discussion key/signature and loading the defined page of comments.
	 *
	 * @param key
	 * @param sig
	 * @param page
	 */
	initialize: function(key, sig) {
		var wrapper = $('#comments'),
			doc = $(document);
		Comments.key = key;
		Comments.sig = sig;
		Comments.wrapper = wrapper;
		// Load the page
		if (wrapper.length && key && sig) {
			var sortOrderPreference = Cookie.read(Comments.commentSortCookie);
			Comments.loadBase(1, null, sortOrderPreference);
		}
		// Setup events
		doc.delegate('#comments a.body-read', 'click', Comments.moreLess);
		doc.delegate('#comments .ui-pagination a', 'click', Comments.loadPage);
		doc.delegate('#comments .ui-pagination .page-next', 'click', Comments.loadPage);
		doc.delegate('#comments .ui-pagination .page-prev', 'click', Comments.loadPage);
		doc.delegate('#comments #comments-sorting-tabs .menu-best a', 'click',
			function(e) {
				if (!$(this).hasClass('tab-active')) {
					Comments.switchSortOrder('best');
				}
			});
		doc.delegate('#comments #comments-sorting-tabs .menu-latest a', 'click',
			function(e) {
				if (!$(this).hasClass('tab-active')) {
					Comments.switchSortOrder('latest');
				}
			});
		if (Core.isIE(6)) {
			doc.delegate('#comments .comments-list li', {
				mouseover: function() {
					$(this).addClass('tile-hover');
				},
				mouseout: function() {
					$(this).removeClass('tile-hover');
				}
			});
		}
	},
	/**
	 * Switches comment sort order when user hits on one of the tabs.
	 *
	 * @param newSortOrder	New comment sort order to switch to.
	 */
	switchSortOrder: function(newSortOrder) {
		Cookie.create(Comments.commentSortCookie, newSortOrder, {
			expires: 8760, // 1 year of hours
			path: '/'
		});
		Comments.loadBase(1, null, newSortOrder);
	},
	/**
	 * Add a comment after validating the form data. If successful, begin the throttler.
	 *
	 * @param button
	 * @param isReply
	 * @param callback
	 */
	add: function(button, isReply, callback) {
		button = $(button);
		button.addClass('disabled').attr('disabled', 'disabled');
		var form = button.parentsUntil('form').parent(),
			detail = form.find('textarea'),
			error = form.find('.comments-error-form');
		error.find('li').hide();
		if (Comments.validate(form)) {
			var noCache = new Date();
			noCache = noCache.getTime();
			var data = {
				detail: $.trim(detail.val()),
				sig: Comments.sig,
				xstoken: Cookie.read('xstoken'),
				base: false,
				nocache: noCache
			};
			if (isReply && Comments.collection) {
				data.replyCommentId = Comments.replyId;
			}
			$.ajax({
				url: Core.baseUrl + '/discussion/' + Comments.key + '/comment.json',
				type: 'POST',
				data: data,
				dataType: 'json',
				success: function(response) {
					//empty the form
					detail.val("");
					//re-enable button
					button.removeClass("disabled").removeAttr("disabled");
					Comments.cancelReply();
					if (response.commentId) {
						// After commenting, always reload using latest comment sort order to
						// make the new comment appear at the top.
						if (!isReply) {
							Comments.loadBase(1, callback, 'latest');
						} else {
							Comments.loadBase(1, callback);
						}
					} else {
						Comments.showErrors(error, response.errors || ['throttled']);
						button.removeClass('disabled').removeAttr('disabled');
					}
				},
				error: function(response) {
					Overlay.open(response.statusText);
					button.removeClass('disabled').removeAttr('disabled');
				}
			});
		} else {
			Comments.showErrors(error, ['required']);
			button.removeClass('disabled').removeAttr('disabled');
		}
		return false;
	},
	/**
	 * Decrement the total count.
	 */
	decrement: function() {
		var node = $('#comments-total'),
			total = parseInt(node.text());
		node.text(total - 1);
	},
	/**
	 * Increment the total count.
	 */
	increment: function() {
		var node = $('#comments-total'),
			total = parseInt(node.text());
		node.text(total + 1);
	},
	/**
	 * Adds a form allowing a user to reply to a comment.
	 *
	 * @param targetId
	 * @param collection
	 * @param author
	 */
	reply: function(targetId, collection, author) {
		if (Comments.throttleTimer !== null) {
			// Don't open reply box while throttled
			return;
		}
		var target = $("#post-" + targetId);
		// If same reply, just toggle visibility
		if (targetId === Comments.replyId) {
			target.next().toggle();
			return;
		}
		Comments.cancelReply();
		Comments.collection = collection;
		Comments.replyId = targetId;
		$('#comments li.nested-reply').remove();
		$('<li/>').addClass('nested-reply').append(Comments.replyForm).insertAfter(
			target);
		Comments.replyInput.focus().val('@' + author + ': ');
	},
	/**
	 * Cancels replying to a comment.
	 */
	cancelReply: function() {
		Comments.replyId = null;
		Comments.collection = null;
		Comments.replyForm.detach();
		Comments.replyInput.val("");
		return false;
	},
	/**
	 * Toggle the display of the delete confirmation message.
	 *
	 * @param id
	 */
	toggleDelete: function(id) {
		$('#post-' + id).find('.comment-foot').toggle().toggleClass('visible');
	},
	/**
	 * Delete a comment after confirming deletion.
	 *
	 * @param id
	 */
	remove: function(id) {
		var noCache = new Date();
		noCache = noCache.getTime();
		$.ajax({
			url: Core.baseUrl + '/discussion/' + id + '/deletecomment.json',
			type: 'POST',
			data: {
				sig: Comments.sig,
				xstoken: Cookie.read('xstoken'),
				nocache: noCache
			},
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					Marketing.trackActivity('Comments', 'Delete Comment');
					$('#post-' + id).fadeOut('normal', function() {
						$(this).remove();
						Comments.decrement();
					});
				}
			}
		});
	},
	/**
	 * Load the base comments markup, including page 1.
	 *
	 * @param page
	 * @param callback
	 * @param view		View type: default (latest), topRated (best)
	 */
	loadBase: function(page, callback, view) {
		var noCache = new Date();
		noCache = noCache.getTime();
		page = page || 1;
		view = view || Core.getHash() || Cookie.read('discussion.sort') || Comments
			.defaultCommentSort;
		$.ajax({
			url: Core.baseUrl + '/discussion/' + Comments.key + '/load.json',
			type: 'GET',
			data: {
				page: page,
				base: true,
				sig: Comments.sig,
				xstoken: Cookie.read('xstoken'),
				nocache: noCache,
				view: view
			},
			dataType: 'html',
			success: function(response) {
				var newWrapper = $(response),
					oldWrapper = Comments.wrapper;
				newWrapper.insertBefore(oldWrapper);
				oldWrapper.remove();
				Comments.wrapper = newWrapper;
				Comments.replyForm = $('#comments-reply-form').detach().show();
				Comments.replyInput = Comments.replyForm.find('textarea');
				ReportPost.initialize(newWrapper, 'comments');
				if (Core.isCallback(callback)) {
					callback();
				}
			},
			error: function(response) {
				Comments.wrapper.addClass('comments-error').find('.subheader-2').toggleClass(
					'hide');
			}
		});
	},
	/**
	 * Load a list of comments for a specific page.
	 *
	 * @param e
	 */
	loadPage: function(e) {
		e.preventDefault();
		e.stopPropagation();
		var noCache = new Date();
		noCache = noCache.getTime();
		var view = Core.getHash() || Cookie.read('discussion.sort') || Comments.defaultCommentSort;
		var self = $(e.currentTarget),
			page = parseInt(self.text()) || parseInt(self.data("pagenum")) || 1,
			list = $('#comments-' + page),
			pages = Comments.wrapper.find('.comments-pages');
		$.ajax({
			url: Core.baseUrl + '/discussion/' + Comments.key + '/load.json',
			type: 'GET',
			data: {
				page: page,
				sig: Comments.sig,
				xstoken: Cookie.read('xstoken'),
				base: false,
				nocache: noCache,
				view: view
			},
			dataType: 'html',
			success: function(response) {
				if (response) {
					Marketing.trackActivity('Comments', 'Load Page#' + page);
					$("#comments-list-wrapper").html(response);
					ReportPost.initialize($("#comments"), 'comments');
				}
			}
		});
	},
	/**
	 * Purge all comments from a discussion.
	 *
	 * @param node
	 * @param notice
	 */
	purge: function(node, notice) {
		if (!confirm(notice)) {
			return false;
		}
		$(node).addClass('disabled').attr('disabled', 'disabled');
		var noCache = new Date();
		noCache = noCache.getTime();
		$.ajax({
			url: Core.baseUrl + '/discussion/' + Comments.key + '/purge.json',
			type: 'POST',
			data: {
				sig: Comments.sig,
				xstoken: Cookie.read('xstoken'),
				nocache: noCache
			},
			dataType: 'json',
			success: function() {
				Comments.loadBase();
			}
		});
	},
	/**
	 * Poll for new data and display a reload button if there are new comments.
	 */
	poll: function() {
		var noCache = new Date();
		noCache = noCache.getTime();
		$.ajax({
			url: Core.baseUrl + '/discussion/' + Comments.key + '/poll.json',
			type: 'GET',
			data: {
				sig: Comments.sig,
				xstoken: Cookie.read('xstoken'),
				nocache: noCache
			},
			dataType: 'json',
			success: function(response) {
				if (response.count && response.count > Comments.count) {
					var diff = response.count - Comments.count,
						node = $('#comments-pull');
					node.find('.pull-single, .pull-multiple').hide();
					node.find('.pull-' + (diff === 1 ? 'single' : 'multiple')).show().find(
						'span').text(diff);
					node.slideDown();
				}
			}
		});
	},
	/**
	 * Lock or unlock a discussion.
	 *
	 * @param node
	 */
	lock: function(node) {
		$(node).addClass('disabled').attr('disabled', 'disabled');
		var noCache = new Date();
		noCache = noCache.getTime();
		$.ajax({
			url: Core.baseUrl + '/discussion/' + Comments.key + '/toggleLock.json',
			type: 'POST',
			data: {
				sig: Comments.sig,
				xstoken: Cookie.read('xstoken'),
				nocache: noCache
			},
			dataType: 'json',
			success: function() {
				Comments.loadBase();
			}
		});
	},
	/**
	 * Open up a comment to read more, or close to read less.
	 *
	 * @param e
	 */
	moreLess: function(e) {
		e.stop();
		var self = $(this),
			parent = self.parent();
		parent.hide();
		parent.siblings().show();
		return false;
	},
	/**
	 * Display the errors within the specific form.
	 *
	 * @param node
	 * @param errors
	 */
	showErrors: function(node, errors) {
		for (var i = 0, l = errors.length; i < l; i++) {
			node.find('.error-' + errors[i]).show();
		}
	},
	/**
	 * Throttle the user by disabling commenting for a duration.
	 *
	 * @param millis
	 */
	throttle: function(millis) {
		var lastTimestamp = Math.ceil(millis / 1000),
			throttler = $('#comments .comments-throttler'),
			buttons = $('#comments .comments-action');
		if (throttler.length && lastTimestamp <= 60) {
			throttler.find('.throttle-time').text(60 - lastTimestamp);
			throttler.show();
			buttons.hide();
			$('.reply-button').attr("data-tooltip", Msg.cms.throttleError).addClass(
				"disabled");
			clearTimeout(Comments.throttleTimer);
			Comments.throttleTimer = setTimeout(function() {
				Comments.countdown(throttler, buttons);
			}, 1000);
		}
	},
	/**
	 * Interval to countdown the throttle timer every second.
	 *
	 * @param throttler
	 * @param buttons
	 */
	countdown: function(throttler, buttons) {
		var seconds = parseInt(throttler.eq(0).find('.throttle-time').text()) || 60,
			timeLeft = seconds - 1;
		clearTimeout(Comments.throttleTimer);
		if (timeLeft <= 0) {
			Comments.throttleTimer = null;
			throttler.hide();
			buttons.show();
			$('.reply-button').removeAttr("data-tooltip").removeClass("disabled");
		} else {
			throttler.find('.throttle-time').text(timeLeft);
			Comments.throttleTimer = setTimeout(function() {
				Comments.countdown(throttler, buttons);
			}, 1000);
		}
	},
	/**
	 * Update the history of the page by changing the URL.
	 *
	 * @param page
	 */
	updateHistory: function(page) {
		History.push({
			key: Comments.key,
			sig: Comments.sig,
			page: page
		}, '?page=' + page + '#comments');
	},
	/**
	 * Update the pagination buttons.
	 *
	 * @param page
	 */
	updatePagination: function(page) {
		Comments.wrapper.find('.ui-pagination').each(function() {
			$(this).find('li').removeClass('current').eq(page - 1).addClass(
				'current');
		});
	},
	/**
	 * Unbury a comment by removing the buried state.
	 *
	 * @param id
	 */
	unbury: function(id) {
		$('#post-' + id).removeClass('comment-buried');
	},
	/**
	 * Validate the form before posting.
	 *
	 * @param form
	 */
	validate: function(form) {
		return ($.trim(form.find('textarea').val()) !== '');
	}
};
/**
 * Functionality related to reporting forum and comment posts
 *
 */
var ReportPost = {
	/**
	 * Wrapper containing posts that can be reported
	 */
	contextWrapper: null,
	/**
	 * Element holding the form to report a post
	 */
	formWrapper: null,
	/**
	 * ID of the offensive post
	 */
	offensiveId: null,
	/**
	 * Name of the offensive post's author
	 */
	offensiveAuthor: null,
	/**
	 * Initialize the post form
	 * @param wrapper - the ID of the wrapper containing posts that can be reported
	 * @param postType - either forums or comments
	 */
	initialize: function(wrapper, postType) {
		if (typeof wrapperId === "string") {
			ReportPost.contextWrapper = $(wrapperId);
		} else {
			ReportPost.contextWrapper = wrapper;
		}
		ReportPost.formWrapper = $("#report-post");
		ReportPost.offensiveId = $("#offensive-post-id");
		ReportPost.offensiveAuthor = $("#offensive-post-author");
		ReportPost.reportReason = $("#report-detail");
		ReportPost.reportSuccess = $("#report-success");
		ReportPost.postType = postType;
		ReportPost.bindEvents();
	},
	bindEvents: function() {
		//bind all current and future upvote nodes
		$("a[data-vote-type='up']", ReportPost.contextWrapper).on("click", function() {
			ReportPost.ratePost(this)
		});
		//bind all current and future downvote nodes
		$("a[data-vote-type='down']", ReportPost.contextWrapper).on("click",
			function() {
				ReportPost.ratePost(this)
			});
		//bind all report nodes within the wrapper
		$("a[data-vote-type='report']", ReportPost.contextWrapper).on("click",
			function() {
				ReportPost.openReportForm(this)
			});
		//bind submit report function
		$(".report-submit", ReportPost.formWrapper).on("click", ReportPost.submitReport);
		//bind cancel report function
		$(".cancel-report", ReportPost.formWrapper).on("click", ReportPost.cancelReport);
		var rateContext = (ReportPost.postType === "forums") ? ".topic-post" :
			".comment-tile";
		/**
		 * IE6 and IE7 will have their post options always showing
		 */
		if (!Core.isIE(6) && !Core.isIE(7)) {
			$(ReportPost.contextWrapper).on("mouseover", rateContext, function(event) {
				if (Core.loggedIn) {
					$(".rate-up, .downvote-wrapper", this).not(".keep-shown").css(
						"visibility", "visible");
				} else {
					$(".rate-post-wrapper", this).css("visibility", "visible");
				}
			}).on("mouseout", rateContext, function(event) {
				if (Core.loggedIn) {
					$(".rate-up, .downvote-wrapper", this).not(".keep-shown").css(
						"visibility", "hidden");
				} else {
					$(".rate-post-wrapper", this).css("visibility", "hidden");
				}
			});
		}
	},
	/**
	 * Rate a post
	 */
	ratePost: function(node) {
		node = $(node);
		//get the details of the offending post
		var postId = node.data("post-id");
		var voteType = node.data("vote-type");
		var voteVal = node.data("report-type");
		var ajaxUrl = Core.baseUrl;
		if (ReportPost.postType === "comments") {
			ajaxUrl += "/discussion/comment/";
		} else {
			ajaxUrl += "/forum/topic/post/";
		}
		ajaxUrl += (postId + "/" + voteType);
		$.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: {
				voteValue: voteVal,
				xstoken: Cookie.read('xstoken')
			},
			success: function(data) {
				if (data.vote > 0) {
					node.addClass("keep-shown").parents(".rate-post-wrapper").addClass(
						"upvoted").removeClass("downvoted");
				} else if (data.vote < 0) {
					node.parents(".rate-option").addClass("keep-shown").parents(
						".rate-post-wrapper").addClass("downvoted").removeClass("upvoted");
				} else {
					$(".keep-shown").removeClass("keep-shown");
					node.parents(".rate-post-wrapper").removeClass("downvoted").removeClass(
						"upvoted");
				}
				if (voteType === "down") {
					node.parents('.downvote-menu').hide();
				}
			},
			error: function(request, status, error) {
				if (voteType === "down") {
					node.parents('.downvote-menu').hide();
				}
				if (request.statusText.length === 0) {
					Core.goTo(window.location + '?login');
					return false;
				}
				Overlay.open(request.statusText);
			}
		});
	},
	/**
	 * Open the form to begin the report process
	 */
	openReportForm: function(node) {
		node = $(node);
		//get the details of the offending post
		var postId = node.data("post-id");
		var voteType = node.data("vote-type");
		var voteVal = node.data("report-type");
		//show the form and add it after the post we want to report
		ReportPost.formWrapper.insertAfter($('#post-' + postId)).show();
		$('#report-success').hide();
		$('#report-table').show();
		//hide the report menu
		node.parents('.downvote-menu').hide();
		//set the details of the offending post
		ReportPost.offensiveId.html(node.data("post-id"));
		ReportPost.offensiveAuthor.html(node.data("post-author"));
	},
	/**
	 * Submit reported post
	 */
	submitReport: function() {
		var postId = ReportPost.offensiveId.html();
		var reportExplanation = $("#report-detail").val();
		//show error if explanation is empty
		if (reportExplanation === "") {
			Overlay.open(Msg.cms.validationError);
			return;
		}
		var ajaxUrl = Core.baseUrl;
		if (ReportPost.postType === "comments") {
			ajaxUrl += "/discussion/comment/";
		} else {
			ajaxUrl += "/forum/topic/post/";
		}
		ajaxUrl += (postId + "/report");
		$.ajax({
			type: "POST",
			url: ajaxUrl,
			data: {
				type: $('#report-reason').val(),
				reason: reportExplanation,
				xstoken: Cookie.read('xstoken')
			},
			success: function(data) {
				$('#report-success').show();
				$('#report-table').hide();
				ReportPost.offensiveId.html("");
				ReportPost.offensiveAuthor.html("");
			},
			error: function(request, status, error) {
				Overlay.open(request.statusText);
			}
		});
	},
	/**
	 * Hide the report form and clear the previous details
	 */
	cancelReport: function() {
		ReportPost.formWrapper.hide();
		ReportPost.offensiveId.html("");
		ReportPost.offensiveAuthor.html("");
		if (ReportPost.postType === "comments") {
			ReportPost.formWrapper.insertAfter("#comments");
		} else {
			ReportPost.formWrapper.insertAfter("#post-list");
		}
	},
	/**
	 * Ignore or unignore a user
	 *
	 * @param node
	 * @param accountId
	 * @param remove	if we want to remove the user from ignore
	 */
	ignoreUser: function(node, accountId, remove) {
		node = $(node);
		node.parents(".ui-context").hide();
		var ignoreList = Cookie.read('bnetUserIgnore')
		ignoreList = (ignoreList != null) ? decodeURIComponent(ignoreList).split(
			',') : [];
		var arrayLoc = $.inArray(accountId.toString(), ignoreList);
		var actionTaken = false;
		if (remove) {
			if (arrayLoc > -1) {
				ignoreList.splice(arrayLoc, 1);
				actionTaken = ReportPost.updateIgnoreList(ignoreList);
			} else {
				Overlay.open(Msg.cms.ignoreNot);
			}
		} else {
			if (arrayLoc < 0) {
				ignoreList.push(accountId);
				if (ignoreList.length > 100) {
					ignoreList.shift();
				}
				actionTaken = ReportPost.updateIgnoreList(ignoreList);
			} else {
				Overlay.open(Msg.cms.ignoreAlready);
			}
		}
		if (actionTaken) {
			window.location.reload();
		}
	},
	updateIgnoreList: function(ignoreList) {
		Cookie.create('bnetUserIgnore', encodeURIComponent(ignoreList.join(',')), {
			path: Core.baseUrl,
			expires: 8760
		});
		return true;
	}
};
"use strict";
/**
 * Opens a lightbox over the content which can display images and videos.
 */
var Lightbox = {
	timeout: 0,
	initialized: false,
	contents: [], //list of images or videos
	currentIndex: 0, //used for paging if content.length > 1
	contentType: "image",
	DEFAULT_WIDTH: 480,
	DEFAULT_HEIGHT: 360,
	anchorClose: true,
	// default config
	config: {
		showTitle: false,
		includeControls: false
	},
	/**
	 * Initializes lightbox and caches relevant DOM elements
	 */
	init: function() {
		//init blackout first (adds to DOM)
		Blackout.initialize();
		//build lightbox elements (adds to DOM)
		Lightbox.build();
		Lightbox.initialized = true;
	},
	/**
	 * Store content data for use later
	 *
	 * @param object content - array of images or videos
	 * @param string contentType - type of content being show in the ligthbox, either "image", "video" or "embed"
	 */
	storeContentData: function(content, contentType) {
		if (!Lightbox.initialized) {
			Lightbox.init();
		}
		//store image list for paging
		Lightbox.contents = content;
		Lightbox.contentType = contentType;
		//store current index
		Lightbox.currentIndex = 0;
		//disable/enable paging
		Lightbox.controls.toggleClass("no-paging", (content.length < 2));
	},
	/**
	 * Loads image into lightbox, adds paging if necessary
	 *
	 * @param array images - array of objects containing title (optional), src, and path (optional) of image to view.
	 *  Example:
	 *	  [{ title: "Image title",
	 *		src:	"http://us.media.blizzard.com/sc2/media/screenshots/protoss_archon_002-large.jpg",
	 *		path:   "/sc2/en/media/screenshots/?view=protoss_archon_002" (omitting the path property will cause the gallery-view icon to hide)
	 *	  }]
	 *
	 */
	loadImage: function(images, dontStore, showIndex) {
		if (!Lightbox.initialized) {
			Lightbox.init();
		}
		//store data
		if (!dontStore) {
			Lightbox.storeContentData(images, "image");
		}
		var index = showIndex || ((typeof images === 'number') ? images : 0);
		//show loading anim and start image fetch
		if (Lightbox.contents[index].src !== "") {
			Lightbox.currentIndex = index;
			Lightbox.setFrameDimensions(Lightbox.DEFAULT_WIDTH, Lightbox.DEFAULT_HEIGHT);
			Lightbox.content.removeAttr("style").addClass("loading").removeClass(
				"lightbox-error").html("");
			Lightbox.show();
			Lightbox.setImage(Lightbox.contents[index]);
		} else {
			Lightbox.error();
		}
	},
	/**
	 * Checks image until its loaded then sets as background image
	 */
	setImage: function(image) {
		if (Core.isIE(6)) {
			if (Lightbox.controls.hasClass("no-paging") && Lightbox.controls.hasClass(
				"no-gallery")) {
				Lightbox.controls.addClass("no-controls").removeClass(
					"no-paging no-gallery");
			}
		}
		// Preload image
		Lightbox.preloadImage(image, function(loadedImage) {
			Lightbox.emptyContent();
			Lightbox.setFrameDimensions(loadedImage.width, loadedImage.height);
			Lightbox.content.html(loadedImage);
			// Update title if supplied
			if (Lightbox.config.showTitle) {
				Lightbox.title.html(image.title || "");
			}
		});
	},
	/**
	 * Loads a video or set of videos with paging in the lightbox
	 *
	 * @param arrray videos - array of video data
	 *
	 *  Example:
	 *	  [{  title:       "Video Title Text", //optional
	 *		  width:	   890,
	 *		  height:	  500,
	 *		  flvPath:	 '/what-is-sc2/what-is-sc2.flv',
	 *		  path:		'/sc2/en/media/videos#/what-is-sc2' //optional
	 *		  showRating:  true, //optional, defaults to true
	 *		  cachePlayer: false //optional, defaults to false
	 *	  }];
	 */
	loadVideo: function(videos, dontStore, showIndex) {
		if (!Lightbox.initialized) {
			Lightbox.init();
		}
		//store data
		if (!dontStore) {
			Lightbox.storeContentData(videos, "video");
		}
		var index = showIndex || ((typeof videos === 'number') ? videos : 0);
		//set first video
		Lightbox.setVideo(videos[index]);
	},
	/**
	 * Sets video in lightbox
	 */
	setVideo: function(video) {
		var currentFlashVars = {
			flvPath: (video.flvBase || Flash.videoBase) + video.flvPath,
			flvWidth: video.width,
			flvHeight: video.height,
			ratingPath: video.customRating,
			captionsPath: "",
			captionsDefaultOn: (Core.locale !== "en-us" && Core.locale !== "en-gb")
		};
		//add rating values
		if (!video.showRating || false) {
			currentFlashVars = $.extend(Flash.defaultVideoFlashVars, currentFlashVars);
		}
		//generate no cache
		var noCache = new Date();
		noCache = "?nocache=" + noCache.getTime();
		//add captions
		if (typeof video.captionsPath !== "undefined" && video.captionsPath !== "") {
			currentFlashVars.captionsPath = video.captionsPath;
		} else {
			delete currentFlashVars.captionsPath;
		}
		//change rating if needed
		if (typeof video.customRating !== "undefined" && video.customRating !== "") {
			if (video.customRating.indexOf("NONE") > -1) {
				delete currentFlashVars.ratingPath;
			} else {
				currentFlashVars.ratingPath = video.customRating;
			}
		} else {
			currentFlashVars.ratingPath = Flash.ratingImage;
		}
		//create a target for the video
		Lightbox.emptyContent();
		$("<div id='flash-target' />").appendTo(Lightbox.content);
		swfobject.embedSWF(Flash.videoPlayer + noCache, "flash-target", video.width,
			video.height, Flash.requiredVersion, Flash.expressInstall,
			currentFlashVars, Flash.defaultVideoParams, {}, Lightbox.flashEmbedCallback
		);
		// Update title if supplied
		if (Lightbox.config.showTitle) {
			Lightbox.title.html(video.title || "");
		}
		Lightbox.setFrameDimensions(video.width, video.height);
		Lightbox.show();
	},
	/**
	 * Loads an embedded video or set of videos with paging into the lightbox
	 */
	loadEmbed: function(embeds, dontStore, showIndex) {
		if (!Lightbox.initialized) {
			Lightbox.init();
		}
		//store data
		if (!dontStore) {
			Lightbox.storeContentData(embeds, "embed");
		}
		var index = showIndex || ((typeof embeds === 'number') ? embeds : 0);
		//set first video
		Lightbox.setEmbed(embeds[index]);
	},
	/**
	 * Sets embedded video in lightbox
	 */
	setEmbed: function(embed) {
		//create a target for the video
		Lightbox.emptyContent();
		$("<object width='" + embed.width + "' height='" + embed.height + "'>" +
			"<param name='movie' value='https://www.youtube.com/v/" + embed.src +
			"?autoplay=1'/>" + "<param name='allowFullScreen' value='true' />" +
			"<param name='allowScriptAccess' value='always'/>" +
			"<param name='wmode' value='opaque' />" +
			"<embed src='https://www.youtube.com/v/" + embed.src +
			"?autoplay=1' type='application/x-shockwave-flash' allowfullscreen='true' allowScriptAccess='always' wmode='opaque' width='" +
			embed.width + "' height='" + embed.height + "'/>" + "</object>").appendTo(
			Lightbox.content);
		// Update title if supplied
		if (Lightbox.config.showTitle) {
			Lightbox.title.html(embed.title || "");
		}
		Lightbox.setFrameDimensions(embed.width, embed.height);
		Lightbox.show();
	},
	/**
	 * View image in the media gallery
	 */
	viewInGallery: function() {
		Tooltip.hide();
		Core.goTo(Lightbox.contents[Lightbox.currentIndex].path);
		return false;
	},
	/**
	 * Dynamically sets border widths/heights based on dimensions so style integrity is maintained
	 */
	setFrameDimensions: function(width, height) {
		if (width === 0 || height === 0) {
			Lightbox.error();
		} else {
			// Explicitly set content container to content height
			Lightbox.content.css({
				height: height + "px"
			});
			// Add title height if it exists
			height = (Lightbox.config.showTitle) ? height + Lightbox.title.height() :
				height;
			// Add control height if they arent an overlay
			height = (Lightbox.config.includeControls) ? height + Lightbox.controls.height() :
				height;
			Lightbox.container.css({
				top: Page.scroll.top + "px",
				width: width + "px",
				height: height + "px"
			});
			Lightbox.borderTop.width(width - 10 + "px");
			Lightbox.borderbottom.width(width - 12 + "px");
			Lightbox.borderRight.height(height - 9 + "px");
			Lightbox.borderLeft.height(height - 9 + "px");
		}
	},
	/**
	 * Toggles class on controls depending on if there is a link to the media gallery
	 *
	 * @param object content
	 */
	checkGalleryLinkDisplay: function(hasPath) {
		Lightbox.controls.toggleClass("no-gallery", hasPath);
	},
	/**
	 * Starts image preload
	 */
	preloadImage: function(loadingImage, callback) {
		var tempImage = new Image();
		$(tempImage).load(function() {
			callback(tempImage);
		});
		tempImage.src = loadingImage.src;
	},
	/**
	 * Show the lightbox.
	 */
	show: function() {
		Blackout.show(function() {
			Lightbox.container[0].style.display = "block";
			Lightbox.checkGalleryLinkDisplay(!(Lightbox.contents[Lightbox.currentIndex]
				.path));
		}, Lightbox.close);
	},
	/**
	 * Hides the lightbox
	 */
	close: function() {
		clearTimeout(Lightbox.timeout);
		Blackout.hide(Lightbox.container.hide());
		//unload swf if needed
		if (Lightbox.contentType === "video") {
			swfobject.removeSWF("flash-target");
		}
		if (Lightbox.contentType === "embed") {
			Lightbox.emptyContent();
		}
		//hide tooltip to prevent artifacts
		Tooltip.hide();
	},
	/**
	 * Clears the content/classes of the viewer, putting it back into a fresh state
	 */
	emptyContent: function() {
		Lightbox.content.removeAttr("style").removeClass("loading lightbox-error").empty();
	},
	/**
	 * Shows lightbox in error state
	 */
	error: function() {
		Lightbox.emptyContent();
		Lightbox.setFrameDimensions(Lightbox.DEFAULT_WIDTH, Lightbox.DEFAULT_HEIGHT);
		Lightbox.content.addClass("lightbox-error").html("Error loading content.");
		Lightbox.show();
	},
	/**
	 * Builds lightbox elements on demand so they aren't in DOM until we need them
	 */
	build: function() {
		Lightbox.anchor = $('<div id="lightbox-anchor" />').click(function() {
			if (Lightbox.anchorClose) {
				Lightbox.close();
			}
		});
		Lightbox.container = $('<div id="lightbox-container" />').mouseover(
			function() {
				Lightbox.anchorClose = false
			}).mouseout(function() {
			Lightbox.anchorClose = true
		}).appendTo(Lightbox.anchor);
		Lightbox.title = $('<h1 id="lightbox-title" />').mouseover(function() {
			Lightbox.anchorClose = false
		}).mouseout(function() {
			Lightbox.anchorClose = true
		}).appendTo(Lightbox.container);
		Lightbox.content = $('<div id="lightbox-content"/>').mouseover(function() {
			Lightbox.anchorClose = false
		}).mouseout(function() {
			Lightbox.anchorClose = true
		}).appendTo(Lightbox.container).click(Lightbox.next);
		//ui-element link element template
		var uiElementLink = $("<a />").addClass("ui-element").attr("href",
			"javascript:;");
		//build controls
		Lightbox.controls = $(
			'<div class="control-wrapper no-paging no-gallery" />');
		Lightbox.controls.append($('<div class="lightbox-controls ui-element" />')
			//previous
			.append(uiElementLink.clone().addClass("previous").click(Lightbox.previous))
			//next
			.append(uiElementLink.clone().addClass("next").click(Lightbox.next))
			//gallery view
			.append(uiElementLink.clone().addClass("gallery-view").click(Lightbox.viewInGallery)
				.mouseover(function() {
					Tooltip.instances.common.show(this, Msg.ui.viewInGallery);
					Tooltip.instances.common.wrapper.css("z-index", "9007");
				}).mouseout(function() {
					Tooltip.instances.common.wrapper.css("z-index", "1000")
				})));
		//create borders before appending (need to access borders later to resize
		var border = $("<div />").addClass("border");
		Lightbox.borderTop = border.clone().attr("id", "lb-border-top");
		Lightbox.borderRight = border.clone().attr("id", "lb-border-right");
		Lightbox.borderbottom = border.clone().attr("id", "lb-border-bottom");
		Lightbox.borderLeft = border.clone().attr("id", "lb-border-left");
		//plain corner element to clone
		var corner = $("<div />").addClass("corner");
		//append everything
		Lightbox.container
			//add corners
			.append(corner.clone().addClass("corner-top-left")).append(corner.clone().addClass(
				"corner-top-right")).append(corner.clone().addClass("corner-bottom-left"))
			.append(corner.clone().addClass("corner-bottom-right"))
			//add borders
			.append(Lightbox.borderTop).append(Lightbox.borderRight).append(Lightbox.borderbottom)
			.append(Lightbox.borderLeft)
			//paging controls
			.append(Lightbox.controls)
			//close button
			.append(uiElementLink.clone().addClass("lightbox-close").click(Lightbox.close));
		//append to body at end to avoid any redraws
		Lightbox.anchor.appendTo("body");
		// toggle so IE will load images properly
		if (Core.isIE(6)) {
			Lightbox.content.show().hide();
		}
	},
	/**
	 * Gets the next image
	 */
	next: function() {
		var totalContent = Lightbox.contents.length;
		if (totalContent > 1) {
			//increment index
			Lightbox.currentIndex++;
			//wrap back to 0
			if (Lightbox.currentIndex >= totalContent) {
				Lightbox.currentIndex = 0;
			}
			if (Lightbox.contentType === "image") {
				Lightbox.setImage(Lightbox.contents[Lightbox.currentIndex]);
			} else if (Lightbox.contentType === "video") {
				Lightbox.setVideo(Lightbox.contents[Lightbox.currentIndex]);
			} else if (Lightbox.contentType === "embed") {
				Lightbox.setEmbed(Lightbox.contents[Lightbox.currentIndex])
			}
		}
	},
	/**
	 * Gets the previous image
	 */
	previous: function() {
		var totalContent = Lightbox.contents.length;
		if (totalContent > 1) {
			//decrement
			Lightbox.currentIndex--;
			if (Lightbox.currentIndex < 0) {
				Lightbox.currentIndex = Lightbox.contents.length - 1;
			}
			if (Lightbox.contentType === "image") {
				Lightbox.setImage(Lightbox.contents[Lightbox.currentIndex]);
			} else if (Lightbox.contentType === "video") {
				Lightbox.setVideo(Lightbox.contents[Lightbox.currentIndex]);
			} else if (Lightbox.contentType === "embed") {
				Lightbox.setEmbed(Lightbox.contents[Lightbox.currentIndex]);
			}
		}
	},
	/**
	 * Display error when flash is not installed
	 */
	flashEmbedCallback: function(e) {
		if (!e.success) {
			//show flash not installed error messages
			Lightbox.content.html(Flash.getFlashError());
		}
	}
};