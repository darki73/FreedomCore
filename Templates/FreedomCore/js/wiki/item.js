
$(function() {
	Item.initialize();
});

var Item = {

	/**
	 * 3D model instance.
	 */
	model: null,

	/**
	 * Init elements on the item details page.
	 */
	initialize: function() {
		$('#wiki .sidebar .fact-list').each(function() {
			var self = $(this);

			if (self.find('li').length <= 0)
				self.parent().remove();
		});
	}
	
}