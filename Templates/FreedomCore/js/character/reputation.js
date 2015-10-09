
$(function() {
	Reputation.initialize();
});

var Reputation = {
	table: null,

	initialize: function() {
		Reputation.container = $('#reputation');

		if (Reputation.container.length) {
			Reputation._createToggleLinks();
		}
	},
	
	_createToggleLinks: function() {
		Reputation.container.delegate('.category-header', 'click', function() {
			$(this).parent('.reputation-category').toggleClass('category-collapsed').children("ul:first").slideToggle('fast');
		})
		.delegate('.faction-header', 'click', function() {
			$(this).parent().parent('.reputation-subcategory').toggleClass('subcategory-collapsed').children("ul:first").slideToggle('fast');
		});
	}
};