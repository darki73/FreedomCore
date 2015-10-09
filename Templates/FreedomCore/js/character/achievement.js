var AchievementsHandler = {
	expandedClass: 	"showing",
	cache: {
		searchTerm:				 	"",
		searchTerms:				[],
		timer:					 	null,
		activeCatId:			 	null
	},
			
	/* 
	* Handle "on page load" and eventHandlers
	*/
	init: function () {
		if (!$("#achievements-search").data("value"))
			$("#achievements-search").data("value", $("#achievements-search").val())

		if (location.hash.length > 1 && location.hash.indexOf("summary") == -1) {
			dm.openEntry(false);
		} else {
			$("#cat-summary").show();
		}
		
		$(".achievement").live("click", function (event) {

			// Don't toggle when clicking links (e.g. reward item)
			if(event.target.nodeName == 'a') {
				return;
			}

			$(".achievement").not(this).removeClass(ah.expandedClass);
			$(this).toggleClass(ah.expandedClass)
			location.replace($(this).attr("data-href"));
		})

		$(".achievements-categories-total .entry-inner").bind("mouseover mouseout", function (e) {
			$(this).toggleClass("hover");
		})

		Input.bind('#achievement-search');
	},

	/* 
	* Allows searching through achievement headline and description
	* @param string term
	*/
	doSearch: function (term, id) {
		ah.cache.searchTerm = term.toLowerCase();
		ah.cache.searchTerms = ah.cache.searchTerm.split(" ");

		$("#search-container").toggleClass("searching", (ah.cache.searchTerm.length > 0));

		clearTimeout(ah.cache.timer);

		ah.cache.timer = setTimeout(function () {
			if(!id)
				DynamicMenu.cache.filtering = DynamicMenu.cache.activeCategoryId;

			$("div[id=cat-"+ (id || DynamicMenu.cache.activeCategoryId) +"] .achievement").each(function () {

			var $listElement = $(this),
				$paragraphNode = $listElement.find("p:first"),
				nodeValue = $paragraphNode.text().toLowerCase(),
				regex, $descNode, $titleNode, foundOccurances = 0;
				
				if(!$listElement.data("paragraphNodeReference")) {
					$listElement.data("paragraphNodeReference", $paragraphNode.clone())
				}

				$paragraphNode.html($listElement.data("paragraphNodeReference").html());

				if(ah.cache.searchTerms.length != 0)  {
					for (var i = 0, len = ah.cache.searchTerms.length; i < len; i++) {
						if (ah.cache.searchTerms[i].length > 0) {
							if (nodeValue.indexOf(ah.cache.searchTerms[i]) != -1) {
								$descNode = $paragraphNode.find("span:first");
								$titleNode = $paragraphNode.find("strong:first");
								regex = new RegExp("(" + ah.cache.searchTerms[i].replace(" ", "\s") + ")", "gi");
								$descNode.html($descNode.html().replace(regex, '<u>$1</u>'));
								$titleNode.html($titleNode.html().replace(regex, '<u>$1</u>'));
								
								foundOccurances++;
							} 
						} else {
							ah.cache.searchTerms.splice(i,1)
						}
					}
				}
				
				if((foundOccurances == ah.cache.searchTerms.length) || ah.cache.searchTerms.length == 0) {
					$listElement.show()
				} else {
					$listElement.fadeOut(300)
				}

				foundOccurances	= 0;
			})
		
		}, 300)
	},

	resetSearch: function (id) {

		AchievementsHandler.doSearch('', id);
		$('#achievement-search').val("");
		Input.reset('#achievement-search');
	}
}
		
var ah = AchievementsHandler;