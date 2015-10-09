"use strict";
// compatibility fix for jquery 1.7.
if(typeof($.fn.addBack) != "function") {
    $.fn.extend({
        addBack: function( selector ) {
            return this.add( selector == null ?
                this.prevObject : this.prevObject.filter(selector)
            );
        }
    });
}

// search for child element or current element
$.fn.findWithSelf = function(selector) {
    return this.find(selector).addBack(selector);
};

/**
 * Primary table utility that handles the sorting, filtering and pagination of a table and its rows.
 *
 * @copyright   2010, Blizzard Entertainment, Inc
 * @class       Table
 * @requires    Core
 * @example
 *
 *		<div id="data-set">
 *			<div class="data-options"> (Pagination) </div>
 *			<div class="data-container">
 *				<table></table> (Table)
 *				or
 *				<ul></ul> (List)
 *				or
 *				<div></div> (Grid)
 *			</div>
 *			<div class="data-options"> (Pagination) </div>
 *		</div>
 *
 *		var dataSet = DataSet.factory('#data-set');
 */
var DataSet = {

	/**
	 * jQuery objects for specific elements.
	 */
	node: null,
	container: null,
	query: '',
	controls: [],
	pages: [],
	none: null,

		// Table specific
		headers: [],
		links: [],

	/**
	 * Configuration.
	 */
	config: {},
	reprocess: false,
	isTable: false,
	isGrid: false,
	isList: false,

	/**
	 * Pagination overwrites.
	 */
	pager: {},

	/**
	 * Filter rules mapping.
	 */
	filters: {},

	/**
	 * A cache of all the data, disconnected from the DOM.
	 */
	source: [],
	cached: [],

	/**
	 * Factory method for generating DataSet instances.
	 *
	 * @param query
	 * @param config
	 */
	factory: function(query, config) {
		var instance = Object.create(DataSet);
			instance.init(query, config);

		return instance;
	},

	/**
	 * Detect if the elements exists, if so apply config and detach nodes.
	 *
	 * @param query
	 * @param config
	 */
	init: function(query, config) {
		this.query = query;
		this.node = $(query);

		// Check if the wrapper exists
		if (!this.node.length) {
			return;
		}

		// Configure
		this.config = $.extend({
			cache: false,
			altRows: true,
			articles: [],
			
			// Sorting
			sorting: true,
			column: 0,
			method: null,
			type: 'asc',

			// Paging
			paging: false,
			page: 1,
			pageCount: 10,
			results: 50,
			totalPages: null,
			totalResults: null,

			// Filtering
			filtering: true,

			// Elements
			elementControls: '.data-options',
			elementContainer: '.data-container', 
			elementRow: '.data-row',
			elementCell: '.data-cell',

			// Callbacks
			beforeSort: null,
			beforePage: null,
			beforeFilter: null,
			beforeProcess: null,
			afterSort: null,
			afterPage: null,
			afterFilter: null,
			afterProcess: null
		}, config);

		// Is it a grid or table?
		var container = this.node.findWithSelf(this.config.elementContainer);

		if (container.length) {
			this.container = container;
			var classType = '';
			
			if (container.findWithSelf('table').length) {
				classType = 'type-table';
				this.isTable = true;
				this.config.elementRow = 'tbody tr';
				this.config.elementCell = 'td';

			} else if (container.findWithSelf('ul').length) {
				classType = 'type-list';
				this.isList = true;
				this.config.elementRow = 'li';

			} else {
				classType = 'type-grid';
				this.isGrid = true;
			}

			container.addClass(classType);

			// Setup the class
			this.reset(true);
			this.setup();

			// Should we cache?
			if (this.config.cache) {
				this.cache();
				this.process();
			}
		}
	},

	/**
	 * Setup batch filters and run the filter process a single time.
	 *
	 * @param filters
	 * @param page
	 */
	batch: function(filters, page) {
		var rule,
			key,
			filter,
			process = false;

		if (this.config.filtering && filters.length > 0) {
			for (var r = 0; rule = filters[r]; ++r) {
				if (rule.length >= 3) {
					filter = rule[0];
					key = rule[1];

					this._mapFilter(filter + '-' + key, rule[2], {
						filter: filter,
						key: key,
						match: this._setNeedle(rule[2]),
						type: rule[3] || '',
						explicit: rule[4] || false
					});
				}
			}

			process = true;
		}

		if (page) {
			process = true;
		}

		if (process) {
			// Cache the rows
			this.cache();

			// Set the page
			this.pager.page = page || 1;

			// Process rows
			this.reprocess = true;
			this.cached = [];

			this._callback('beforeFilter');
			this.process(true);
			this._callback('afterFilter');
		}
	},

	/**
	 * Once DOM is ready, detach rows into the class and cache results.
	 *
	 * @param recache
	 */
	cache: function(recache) {
		if (this.source.length && !recache) {
			return;
		}

		var config = this.config,
			row = null,
			rows = this.container.find( config.elementRow ).detach(),
			subrows = [],
			dataImplicit = [],
			dataExplicit = [],
			source = [];

		if (this.isTable) {
			var count = this.container.find('thead th').length,
				rowspan = null,
				columns = [],
				column = null;
		}

		for (var i = 0; row = rows[i]; i++) {
			row = $(row);
			dataImplicit = [];
			dataExplicit = [];

			// Detach no results separately
			if (row.hasClass('no-results')) {
				this.none = row.removeClass('row-hidden').show().detach();
				continue;
			}

			// Cache table data
			if (this.isTable) {
				columns = row[0].getElementsByTagName( config.elementCell );

				// Fewer tbody columns than thead columns; must have rowspan/subrows
				if (columns.length < count) {
					continue;
				}

				for (var j = 0; column = columns[j]; j++) {
					dataImplicit[j] = this._getText(column, false, this.articles);
					dataExplicit[j] = $(column).text().trim().toLowerCase();
				}

				rowspan = Core.isIE() ? row.find('td[rowspan!="1"]') : row.find('td[rowspan]');
				subrows = [];

				if (rowspan.length === 1) {
					for (var k = 1, len = rowspan.attr('rowspan'); k < len; k++) {
						subrows.push($(rows[k]).detach());
					}
				}

			// Cache grid/list data
			} else {
				dataImplicit[0] = this._getText(row, false, this.articles);
				dataExplicit[0] = row.text().trim().toLowerCase();
			}

			source.push([
				dataImplicit,
				dataExplicit,
				row.detach(),
				subrows
			]);
		}

		this.source = source;
	},

	/**
	 * Filter the rows using various techniques.
	 *
	 * @param filter	accepts: column (table), row, class
	 * @param key
	 * @param value
	 * @param type
	 * @param explicit
	 */
	filter: function(filter, key, value, type, explicit) {
		if (!this.config.filtering) {
			return;
		}

		// Cache the rows
		this.cache();

		// Map filter rule
		this._mapFilter(filter + '-' + key, value, {
			filter: filter,
			key: key,
			match: this._setNeedle(value),
			type: type || '',
			explicit: explicit || false
		});

		// Reset page back to 1
		this.pager.page = 1;

		// Process rows
		this.reprocess = true;
		this.cached = [];

		this._callback('beforeFilter');
		this.process(true);
		this._callback('afterFilter');
	},

	/**
	 * Paginate a group of rows.
	 *
	 * @param page
	 */
	paginate: function(page) {
		if (!this.config.paging) {
			return;
		}

		// Cache the results
		this.cache();

		// Set the new page
		this.pager.page = page;

		// Process rows
		this._callback('beforePage');
		this.process();
		this._callback('afterPage');

		// Scroll to top
		Core.scrollTo(this.container);
	},

	/**
	 * Process all the current rows against the defined filters. Once complete, paginate.
	 *
	 * @param filtered
	 */
	process: function(filtered) {
		var source = (this.cached.length) ? this.cached : this.source,
			length = (source.length - 1),
			fragment = document.createDocumentFragment(),
			display = '',
			config = this.config,
			cache = [],
			count = 0,
			row = null,
			subrows = null,
			append = false,
			i = 0;

		if (this.isGrid) {
			display = Core.isIE() ? 'block' : 'inline-block';
		}

		fragment = this._callback('beforeProcess', fragment);

		// Paging variables
		if (config.paging) {
			var page = this.pager.page || 1,
				start = (config.results * (page - 1)),
				pageCount = 0;
		}

		if (this.none !== null) {
			this.none.detach();
		}

		if (source.length) {

			// Loop over first and filter + cache
			for (i = 0; i <= length; i++) {
				row = source[i][2];

				if (config.filtering && this.filters.count > 0) {
					if (!this._processFilters(row, source[i])) {
						continue;
					}
				}

				cache.push([
					source[i][0],
					source[i][1],
					row,
					source[i][3]
				]);
			}

			// Loop over again for paging
			count = cache.length;
			length = (cache.length - 1);

			if (start > count) {
				start = 0;
				page = 1;
			}

			for (i = 0; i <= length; i++) {
				row = cache[i][2];
				subrows = cache[i][3];
				append = true;

				if (config.paging) {
					if (i >= start && pageCount < config.results) {
						pageCount++;
					} else {
						append = false;
					}
				}

				if (append) {
					row.css('display', display);

					if (config.altRows) {
						row.removeClass('row1 row2').addClass('row' + ((i % 2) ? 2 : 1));
					}

					fragment.appendChild(row[0]);

					if (this.isTable && subrows.length > 0) {
						for (var m = 0, subrow; subrow = subrows[m]; m++) {
							fragment.appendChild(subrow[0]);
						}
					}
				}
			}

			this.cached = cache;
		}

		// No results
		if (count <= 0 && this.none !== null) {
			fragment.appendChild(this.none[0]);
		}

		// Update paging
		if (config.paging) {
			this.pager = {
				page: page,
				totalPages: Math.ceil(count / config.results),
				totalResults: count
			};

			if (filtered) {
				this._buildPagination();
			}

			this._updateResults(page);
			this._updatePagination(page);
		}

		fragment = this._callback('afterProcess', fragment);

		// Append document fragment
		if (this.isTable) {
			this.container.find('tbody').empty().append(fragment);

		} else if (this.isList) {
			this.container.find('ul').empty().append(fragment);

		} else {
			this.container.empty().append(fragment);
		}

		this.reprocess = false;
	},

	/**
	 * Reset the class back to defaults, but do not clear the source cache.
	 *
	 * @param exit
	 */
	reset: function(exit) {
		this.cached = [];

		// Paging
		this.pager = {
			page: 1,
			totalPages: null,
			totalResults: null
		};

		// Filtering
		this.filters = {
			map: {},
			rules: [],
			count: 0
		};

		if (exit) {
			return;
		}

		// Sorting
		var config = this.config;

		if (config.method !== null) {
			this.sort(config.method, config.type, config.column);
		}

		// Process rows
		this.process(true);
	},

	/**
	 * Bind the sorting event handlers, prepare filters, do magic.
	 */
	setup: function() {
		var config = this.config;
		
		// Sorting
		if (config.sorting && this.isTable) {
			var cells = this.container.find('thead th'),
				headers = [],
				links = [],
				index = 0;

			for (var i = 0; i < cells.length; i++) {
				var self = $(cells[i]),
					colspan = self.attr('colspan') || 0,
					link = self.find('a.sort-link');

				link
					.data('index', index)
					.unbind()
					.click( $.proxy(this._sortCallback, this) );

				headers.push( self );
				links.push( link );

				if (colspan) {
					for (var c = 1; c < colspan; c++) {
						headers.push( null );
						links.push( null );
						index++;
					}
				}

				index++;
			}

			this.headers = headers;
			this.links = links;

			// Run the default sort
			if (config.method !== null) {
				this.sort(config.method, config.type, config.column);
			}
		}

		// Paging
		if (config.paging) {
			this.controls = this.node.find(config.elementControls);

			if (config.totalResults > config.results) {
				if (!config.totalPages) {
					config.totalPages = Math.ceil(config.totalResults / config.results);
				}

				if (!config.pageCount) {
					config.pageCount = 10;
				}

				this._buildPagination();

				if (config.page > 1) {
					this.paginate(config.page);
				} else {
					this._updatePagination(1);
				}
			}
		}
	},

	/**
	 * Sort the rows based on a column.
	 *
	 * @param method	accepts: default, numeric, date
	 * @param type		accepts: asc, desc, reverse
	 * @param column
	 */
	sort: function(method, type, column) {
		if (!this.config.sorting || typeof method === 'undefined') {
			return;
		}

		if (typeof type === 'undefined' || !type) {
			type = 'asc';
		}

		if (typeof column === 'undefined' || !type) {
			column = 0;
		}

		// Cache the rows
		this.cache();

		// Setup callback
		var callback = this._callback('beforeSort', [method, type, column]);

		if (callback.length) {
			method = callback[0] || method;
			type = callback[1] || type;
			column = callback[2] || column;
		}

		// Set the active link tab in the headers
		if (this.isTable && column >= 0) {
			var tab = this._activeHeader(column);

			if (tab.is('.up, .down')) {
				if (tab.hasClass('up')) {
					tab.removeClass('up').addClass('down');
					type = 'desc';
				} else {
					tab.removeClass('down').addClass('up');
					type = 'asc';
				}
			} else {
				tab.addClass((type === 'asc') ? 'up' : 'down');
			}
		}

		// Avoid descending sort since reverse() is faster
		if (this.reprocess || type !== 'reverse') {
			DataSetStatic.column = column;
			
			// Numeric
			if (method === 'numeric') {
				this.source.sort(DataSetStatic.sortNumeric);
				this.cached.sort(DataSetStatic.sortNumeric);

			// Date
			} else if (method === 'date') {
				this.source.sort(DataSetStatic.sortDate);
				this.cached.sort(DataSetStatic.sortDate);

			// Alphabetic
			} else {
				this.source.sort(DataSetStatic.sortNatural);
				this.cached.sort(DataSetStatic.sortNatural);
			}

			if (type === 'desc') {
				this.source.reverse();
				this.cached.reverse();
			}
			
		} else if (type === 'reverse') {
			this.source.reverse();
			this.cached.reverse();
		}

		// Process rows
		this.reprocess = false;
		this.process();
		this._callback('afterSort');
	},

	/**
	 * Find the active column header.
	 *
	 * @param column
	 */
	_activeHeader: function(column) {
		var arrow,
			link;

		for (var i = 0, l = this.links.length; i < l; i++) {
			if (this.links[i]) {
				link = this.links[i];

				if (i === column) {
					arrow = link.find('.arrow');
				} else {
					link.find('.arrow').attr('class', 'arrow');
				}
			}
		}

		return arrow;
	},

	/**
	 * Build the paging link.
	 *
	 * @param text
	 * @param page
	 */
	_buildLink: function(text, page) {
		var li = $('<li/>').hide();

		$('<a/>')
			.html('<span>' + text + '</span>')
			.attr('href', '#page=' + page)
			.data('page', page)
			.click( $.proxy(this._pageCallback, this) )
			.appendTo(li);

		return li;
	},

	/**
	 * Create the pagination links and save to a variable.
	 */
	_buildPagination: function() {
		if (!this.controls.length) {
			return;
		}

		var ul = this.controls.find('.ui-pagination'),
			total = this.config.totalPages;

		if (this.pager.totalPages !== null) {
			total = this.pager.totalPages;
		}

		// Builds items
		ul.empty();

		if (total > 1) {
			var li;

			for (var i = 1; i <= total; ++i) {
				li = this._buildLink(i, i);
				li.appendTo(ul);
			}

			// Build expanders
			if (total > this.config.pageCount) {
				var first = this._buildLink(Msg.grammar.first, 1),
					last = this._buildLink(Msg.grammar.last, total);

				first.addClass('first-item').prependTo(ul);
				last.addClass('last-item').appendTo(ul);
			}
		}

		// Save reference
		this.pages = this.node.find(this.config.elementControls + ' .ui-pagination');
	},

	/**
	 * Trigger the callback if it has been set.
	 *
	 * @param callback
	 * @param arg
	 * @return mixed
	 */
	_callback: function(callback, arg) {
		if (Core.isCallback(this.config[callback])) {
			return this.config[callback](this, arg);
		}

		return arg;
	},

	/**
	 * Extracts the text from the cell or data attribute and removes articles.
	 *
	 * @param cell
	 * @param returnText
	 * @param articles
	 */
	_getText: function(cell, returnText, articles) {
		if (typeof cell === 'undefined') {
			return '';
		}

		cell = $(cell);
		articles = articles || [];

		var text = (typeof cell.data('raw') === 'undefined') ? cell.text() : cell.data('raw').toString();
			text = text.trim().toLowerCase();

		if (articles.length === 0 || returnText) {
			return text;
		}

		for (var i = 0, article; article = articles[i]; i++) {
			if (text.indexOf(article + ' ') === 0) {
				text = text.replace(new RegExp('^' + article + ' '), '');
			}
		}

		return text;
	},

	/**
	 * Add or remove a mapping filter.
	 *
	 * @param key
	 * @param value
	 * @param filter
	 */
	_mapFilter: function(key, value, filter) {
		var map = this.filters.map,
			rules = this.filters.rules;

		if (map[key]) {
			var state = rules[map[key]].match;

			if (state.length > 0 && filter.match.length === 0) {
				this.filters.count--;
			} else if (state.length === 0 && filter.match.length > 0) {
				this.filters.count++;
			}

			rules[map[key]] = filter;

		} else {
			map[key] = parseInt(rules.length.toString());
			rules.push( filter );

			if (filter.match.length > 0) {
				this.filters.count++;
			}
		}
	},

	/**
	 * Pagination callback per paging link click.
	 *
	 * @param e
	 */
	_pageCallback: function(e) {
		e.preventDefault();

		var self = $(e.currentTarget),
			page = self.data('page');

		this.paginate(page);

		if (typeof Filter !== 'undefined') {
			Filter.addParam('page', page);
			Filter.applyQuery();
		}

		return false;
	},

	/**
	 * Run the current filters on the row.
	 * Returns true if the filter has passed.
	 *
	 * @param row
	 * @param cache
	 * @return boolean
	 */
	_processFilters: function(row, cache) {
		var rules = this.filters.rules,
			length = rules.length,
			map = this.filters.map,
			pass = true,
			rule,
			text,
			key;

		for (var i = 0; i < length; i++) {
			pass = true;
			rule = rules[i];
			key = rule.filter + '-' + rule.key;

			if (typeof map[key] === 'undefined' || i !== map[key] || rule.match.length === 0) {
				continue;
			}

			// Row or column
			if (rule.filter === 'row' || rule.filter === 'column') {
				text = cache[(rule.explicit ? 1 : 0)];

				if (rule.filter === 'column') {
					text = text[rule.key || 0];
				}

				if (text === "") {
					pass = false;
				} else {
					if ($.type(text) === 'array') {
						text = text.join(' ').toLowerCase();
					}

					if (DataSetStatic[rule.type]) {
						pass = DataSetStatic[rule.type](text, rule.match);
					} else {
						pass = DataSetStatic.matches(text, rule.match);
					}
				}

			// Class
			} else if (rule.filter === 'class' && !DataSetStatic.contains(row.attr('class'), rule.match)) {
				pass = false;
			}

			if (pass === false) {
				break;
			}
		}

		return pass;
	},

	/**
	 * Parse the needle before matching.
	 *
	 * @param needle
	 */
	_setNeedle: function(needle) {
		if (typeof needle === 'undefined') {
			return "";
		} else if (typeof needle !== 'string') {
			return needle;
		}

		needle = needle.trim().toLowerCase();

		if (needle.indexOf(' ') >= 0 && needle !== "") {
			needle = needle.replace(/\s+/g, ' ').split(' ');
		}

		return needle;
	},

	/**
	 * Callback triggered for sort link clicks.
	 *
	 * @param e
	 */
	_sortCallback: function(e) {
		e.stopPropagation();

		var node = $(e.currentTarget),
			method = 'default';

		if (node.hasClass('numeric')) {
			method = 'numeric';
		} else if (node.hasClass('date')) {
			method = 'date';
		}

		this.sort(method, null, node.data('index'));

		return false;
	},

	/**
	 * Update the number for currently viewed results.
	 *
	 * @param page
	 */
	_updateResults: function(page) {
		if (!this.controls.length) {
			return;
		}

		var total = this.config.totalResults,
			start = (1 + (this.config.results * (page - 1))),
			end = (start - 1) + this.config.results;

		if (this.pager.totalResults !== null) {
			total = this.pager.totalResults;
		}

		if (end > total) {
			end = total;
		}

		if (start <= 0) {
			start = 1;
		} else if (total <= 0) {
			start = 0;
		}

		this.controls
			.find('.results-start').html(start).end()
			.find('.results-end').html(end).end()
			.find('.results-total').html(total).end();
	},

	/**
	 * Update the pagination links to the currently active.
	 * Also truncate surrounding links.
	 *
	 * @param page
	 */
	_updatePagination: function(page) {
		if (!this.pages.length) {
			return;
		}

		var config = this.config,
			half = Math.round(config.pageCount / 2),
			display = 'inline-block';

		this.pages.each(function() {
			var li = $(this).find('li'),
				total = li.length,
				index = page;

			if (total <= 0) {
				return;
			} else if (total <= config.pageCount) {
				index--;
			}

			// Hide all items
			li.removeClass('current');

			// Set active
			li.eq(index).addClass('current');

			if (total > config.pageCount) {
				total = total - 2;
				li.hide();

				// End
				if (page >= (total - half)) {
					li.slice(-config.pageCount).not('.last-item').css('display', display);
					li.slice(0, 1).css('display', display);

				// Middle
				} else if (page > (half + 1) && page < (total - half)) {
					li.slice((page - half) + 1, (page + half)).css('display', display);
					li.slice(0, 1).css('display', display);
					li.slice(-1).css('display', display);

				// Start
				} else {
					li.slice(1, config.pageCount).css('display', display);
					li.slice(-1).css('display', display);
				}
			} else {
				li.css('display', display);
			}
		});
	}

};

/**
 * Static functions used by the table class.
 */
var DataSetStatic = {

	/**
	 * Detect if the string/array is within an array.
	 *
	 * @param text
	 * @param match
	 */
	contains: function(text, match) {
		if (typeof text === 'string')  {
			text = text.split(' ');
		}

		var valid = false,
			isArray = ($.type(match) === 'array');

		for (var i = 0, test; test = text[i]; ++i) {
			if ((isArray && $.inArray(test, match) >= 0) || (!isArray && test.indexOf(match) >= 0)) {
				valid = true;
				break;
			}
		}

		return valid;
	},

	/**
	 * Detect if the string contains a character.
	 *
	 * @param text
	 * @param match
	 */
	matches: function(text, match) {
		if (typeof text === 'undefined') {
			return false;

		} else if (typeof match === 'number') {
			return (match == text);
			
		} else if (typeof match === 'string') {
			return (text.indexOf(match.toLowerCase()) >= 0);

		} else {
			var valid = true;

			for (var i = 0, test; test = match[i]; ++i) {
				if (text.indexOf(test.toLowerCase()) === -1) {
					valid = false;
					break;
				}
			}

			return valid;
		}
	},

	/**
	 * Values are equal.
	 *
	 * @param text
	 * @param match
	 */
	equals: function(text, match) {
		return (match == text);
	},

	/**
	 * Values are not equal.
	 *
	 * @param text
	 * @param match
	 */
	notEquals: function(text, match) {
		return (match != text);
	},

	/**
	 * Values and type are exact.
	 *
	 * @param text
	 * @param match
	 */
	exact: function(text, match) {
		return (match === text);
	},

	/**
	 * Value is within a specific range.
	 *
	 * @param text
	 * @param match
	 */
	range: function(text, match) {
		return (parseInt(text) >= match[0] && parseInt(text) <= match[1]);
	},

	/**
	 * Value is greater than a number.
	 *
	 * @param text
	 * @param match
	 */
	greaterThan: function(text, match) {
		return (parseInt(text) > match);
	},

	/**
	 * Value is greater or equals to a number.
	 *
	 * @param text
	 * @param match
	 */
	greaterThanEquals: function(text, match) {
		return (parseInt(text) >= match);
	},

	/**
	 * Value is less than a number.
	 *
	 * @param text
	 * @param match
	 */
	lessThan: function(text, match) {
		return (parseInt(text) < match);
	},

	/**
	 * Value is less or equal to a number.
	 *
	 * @param text
	 * @param match
	 */
	lessThanEquals: function(text, match) {
		return (parseInt(text) <= match);
	},

	/**
	 * Value starts with a specific character(s).
	 *
	 * @param text
	 * @param match
	 */
	startsWith: function(text, match) {
		return (text.substr(0, match.length) === match);
	},

	/**
	 * Value ends with a specific character(s).
	 *
	 * @param text
	 * @param match
	 */
	endsWith: function(text, match) {
		return (text.substr(-match.length) === match);
	},

	/**
	 * The column to sort against.
	 */
	column: 0,

	/**
	 * Sort the data numerical.
	 *
	 * @param a
	 * @param b
	 */
	sortNumeric: function(a, b) {
		var x = a[0][DataSetStatic.column],
			y = b[0][DataSetStatic.column];

		return parseFloat(x) - parseFloat(y);
	},

	/**
	 * Sort the data by date.
	 *
	 * @param a
	 * @param b
	 */
	sortDate: function(a, b) {
		var x = Date.parse(a[0][DataSetStatic.column]),
			y = Date.parse(b[0][DataSetStatic.column]);

		return parseFloat(x) - parseFloat(y);
	},

	/**
	 * Sort the data natural.
	 *
	 * @param a
	 * @param b
	 */
	sortNatural: function(a, b) {
		var x = a[0][DataSetStatic.column],
			y = b[0][DataSetStatic.column];

		return ((x < y) ? -1 : ((x > y) ? 1 : 0));
	}

};