// Shared between static and character-specific profession pages
var RecipeTable = {

	container: null,

	init: function(container, options) {

		RecipeTable.container = $(container);
		$.extend(RecipeTable, options);

		setTimeout(RecipeTable.bindEvents, 50);
	},

	bindEvents: function() {

		RecipeTable.bindMultiSkillUpTooltip();
		RecipeTable.bindSkillTooltip();
	},

	bindMultiSkillUpTooltip: function() {
		RecipeTable.container.delegate('span.recipe-multiskillup', 'mouseover', function() {

			var self = $(this),
				tooltip = $('#skill-tooltip-multiskillup'),
				orange = tooltip.find('span.color-s1');

			if (!tooltip.length)
				return;

			if(orange.length) {
				tr = self.parent().parent();
				var ranks = RecipeTable.getSkillUpChanceFromNode(tr.find('span[data-skillupchance]'));
				if(!ranks) {
					return;
				}

				var nextRank;
				for(var i = 0; i < ranks.length; ++i) {
					if(ranks[i] > ranks.orange) {
						nextRank = ranks[i];
						break;
					}
				}
				orange.text(ranks.orange + '-' + (nextRank-1));
			}

			tooltip.find('span.value').text(self.text());

			Tooltip.show(this, tooltip.html());
		});
	},

	bindSkillTooltip: function() {
		RecipeTable.container.delegate('span[data-skillupchance]', 'mouseover', function() {

			var self = $(this),
				ranks = RecipeTable.getSkillUpChanceFromNode(self),
				tooltip = $('#skill-tooltip');

			if (!tooltip.length || !ranks)
				return;

			tooltip.find('span.profession-name').text(RecipeTable.name);
			tooltip.find('span.req-skill').text(self.text());

			var lis = tooltip.find('li')

			lis.hide();

			for (var i = 0; i < ranks.length; i++) {

				var rank = ranks[i];
				if(rank > 0) {
					var li = $(lis.get(i));
					li.find('span.value').html(rank);
					li.show();
				}
			}

			Tooltip.show(this, tooltip.html());
		});
	},

	getSkillUpChanceFromNode: function(node) {

		var ranks = node.data('skillupchance');
		if(!ranks) {
			return null;
		}

		ranks = ranks.split(',');
		if(ranks.length != 4) {
			return null;
		}

		for(var i = 0; i < ranks.length; ++i) {
			ranks[i] = parseInt(ranks[i]);
		}

		ranks.orange = ranks[0];
		ranks.yellow = ranks[1];
		ranks.green = ranks[2];
		ranks.gray = ranks[3];

		return ranks;
	}

};