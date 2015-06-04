
$(function() {
	setTimeout(Profession.initialize, 50); // Small delay so the loading animation can be seen
});

var Profession = {

	/**
	 * Table instance.
	 */
	table: null,
	typeId: 0,

	/**
	 * Initialize binds and filters.
	 */
	initialize: function() {
		if (!Profession.table)
			return;
		
		// Timeouts used to let the browser breathe/refresh
		setTimeout(Profession.initFilters, 1);
		setTimeout(Profession.initEvents, 1);
		
	},

	initFilters: function() {
		Filter.initialize(Profession.applyFilters);

		$('#profession-loading').hide();
		$('#professions').show();
	},

	initEvents: function() {
		$('#profession-filters .tabs a').click(Profession.filterStatus);
		Input.bind('#filter-keyword');
		Filter.bindInputs('#profession-filters', Profession.filter);

		// Tooltip for unknown secondary skills
		$('#profile-sidebar-menu').delegate('li.disabled a', 'mouseover', function() {
			var $this = $(this);
			Tooltip.show(this, Wow.createSimpleTooltip($this.text(), $('#profession-recipe-unknown').text()));
		});
	},

	/**
	 * Apply filters if present in hash.
	 *
	 * @param query
	 */
	applyFilters: function(query) {
		var rules = [];

		if (!query.status) {
			query.status = 'learned';
		}

		$('#profession-filters .tabs')
			.find('a').removeClass('tab-active').end()
			.find('a[data-status="'+ query.status +'"]').addClass('tab-active').end();

		rules.push(['class', 'status', query.status]);

		if (query.filter) {
			$('#filter-keyword').val(query.filter).trigger('keyup');
			rules.push(['row', 'filter', query.filter, null, true]);
		}

		// Sort non-numeric for arch profession
		var method = (Profession.typeId == 794) ? 'default' : 'numeric';

		Profession.table.batch(rules, query.page || 1);
		Profession.table.sort(method, 'desc', 4);
	},

	/**
	 * Filter based on input fields.
	 *
	 * @param data
	 */
	filter: function(data) {
		if (data.name == 'filter') {
			Profession.table.filter('row', data.name, data.value, null, true);
			
			Filter.addParam('filter', data.value);
			Filter.applyQuery();
		}
	},

	/**
	 * Filter based on status tabs.
	 */
	filterStatus: function() {
		var node = $(this),
			filter = node.data('status');

		node.siblings('a').removeClass('tab-active');
		node.addClass('tab-active');
			
		Profession.table.filter('class', 'status', filter);

		Filter.addParam('status', filter);
		Filter.applyQuery();
	}
	
}