
$(function() {
	RealmStatus.initialize();
});

var RealmStatus = {

	/**
	 * Table instances.
	 */
	userRealms: DataSet.factory('#user-realms', { column: 1, method: 'default' }),

	allRealms: DataSet.factory('#all-realms', { column: 1, method: 'default' }),

	/**
	 * Set filter binds.
	 */
	initialize: function() {
		Input.bind('#filter-name');
		Filter.initialize(RealmStatus.applyFilters);

		// Bind input event handlers
		Filter.bindInputs('#realm-filters', function(data) {
			Filter.addParam(data.name, data.value);
			Filter.applyQuery();

			RealmStatus.activeTable().filter('column', data.column, data.value, (data.name != 'name'));
		});
	},

	/**
	 * Grab the currently visible table.
	 */
	activeTable: function() {
		if ($('#user-realms').is(':visible'))
			return RealmStatus.userRealms;
		else
			return RealmStatus.allRealms;
	},

	/**
	 * Apply filters on page load based off hash query.
	 *
	 * @param query
	 */
	applyFilters: function(query) {
		var columns = [];

		$.each(query, function(key, value) {
			var input = $("#filter-"+ key);
				input.val(value);

			if (key == 'queue' && value == 'true')
				input.attr('checked', 'checked');

			columns.push([input.data('column'), value, 'exact']);
		});

		RealmStatus.activeTable().batch({
			columns: columns
		});
	},

	/**
	 * Reset the filters and tables.
	 */
	reset: function(){
		Filter.reset();
		Filter.resetInputs('#realm-filters');

		if (RealmStatus.userRealms.node.length) {
			RealmStatus.userRealms.reset();
		}
		RealmStatus.allRealms.reset();
	},

	/**
	 * Open the filter menu.
	 *
	 * @param target
	 */
	filterToggle: function(target){
		$(target).children().toggle();
		$('#realm-filters').slideToggle(150);
	},

	/**
	 * Swap the realm lists.
	 *
	 * @param node
	 * @param allRealms
	 */
	toggleRealms: function(node, allRealms){
		$('#realm-status .tab-menu a').removeClass('tab-active');
		$(node).addClass('tab-active');

		if (allRealms) {
			$('#all-realms').show();
			$('#user-realms').hide();
		} else {
			$('#all-realms').hide();
			$('#user-realms').show();
		}
	}
	
};