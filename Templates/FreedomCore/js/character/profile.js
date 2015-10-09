/*TODO: its being rewritten currently.*/
var DynamicMenu = {
	$categoriesContainer:		 $("#profile-sidebar-menu"),
	$swipeInContainer:			 $("#swipe-in-container"),
	$swipeOutContainer:			$("#swipe-out-container"),
	backlinksArray:				[],
	$backlinkHTML:				$('<li><a class="back-to" href="#summary"><span class="arrow"><span class="icon">name</span></span></a></li>'),
	config: {
		"section":				 "statistics", //used for prefixing important elements such as #<section>-list
		"linkActiveClass":		 "active"
	},
	cache: {
		"activeCategoryId": null,
		filtering: false
	},

	/*
	 * Handle linkClass and eventHandlers on category links
	 */
	init: function (options) {

		//merge options
		if (options) $.extend(dm.config, options);

		/*
		 * Prefill breadcrumb array with summary link and menu root link
		 */
		var $mainMenuLink = $("li a:first", dm.$categoriesContainer);
		dm.backlinksArray[0] = {
			"title": $mainMenuLink.find(".icon").text(),
			"nodeRef": $mainMenuLink[0].href
		};

		dm.backlinksArray[1] = {
			"title": $("li.root-menu:first", dm.$categoriesContainer).next().find("a:first .icon").text(),
			"nodeRef": dm.$categoriesContainer,
			"class": "root-menu"
		};

		/*
		 *	Attach event handlers on relevant links
		 */
		$(".profile-sidebar-menu a").live("click", function(event) {

			if($(this).hasClass("active"))
				return false;

			/* init swipe for categories containing additional categories */
			if ($(this).hasClass("has-submenu")) {
				dm._doSlide($(this).parent(), "in")
			} else if ($(this).hasClass("back-to")) {
				var $backLinks = $("#profile-sidebar-menu a.back-to");

				if (typeof $(this).data("nodeRef") == 'string' || $(this).index($backLinks) == 0) {
					window.location = $(this).data("href") || this.href;
				} else {
					dm._doSlide($(this).data("nodeRef"), "out", $(this).data("index"))
				}
			} else { /* handle styling of simple links */
				$(".active", $(this).parents("ul"))
					 .removeClass(dm.config.linkActiveClass);
				$(this).addClass(dm.config.linkActiveClass);
			}

			//if # in href, populate list on click and update location.hash
			if(this.href.indexOf("#") != -1) {
				if (!$(this).data("data-hash")) {
					$(this).data("data-hash", this.href.substring(this.href.indexOf("#")));
				}

				var categories = DynamicMenu._parseCategoryFromURL(this.href);
				DynamicMenu._populateList(categories);
				location.hash = $(this).data("data-hash");
			}

			event.preventDefault();
			return true;
		});

		dm._generateSlideContainer();
	},

	/*
	 * Parse category info from url
	 * @param string url
	 * @return array
	 */
	_parseCategoryFromURL: function(url) {

		var hash = url.substring(url.lastIndexOf("#") + 1);
		return hash.split(":")
	},

	/*
	 * Generates placeholder divs used for DOM node copying
	 */
	_generateSlideContainer: function () {
		var $swipeIn = $('<div/>', {
			id: "swipe-in-container",
			"class": "swipe-container"
		});

		var $swipeOut = $('<div/>', {
			id: "swipe-out-container",
			"class": "swipe-container"
		});

		var $ul = $('<ul/>', {
			"class": "profile-sidebar-menu"
		});

		$ul.clone().appendTo($swipeIn, $swipeOut);
		$("#profile-sidebar-menu").after($swipeIn, $swipeOut);

		dm.$swipeInContainer = $("#swipe-in-container");
		dm.$swipeOutContainer = $("#swipe-out-container");
	},

	/*
	 * Generate back links
	 * @param DOMelement (LI) listElement
	 * @param string direction
	 * @param string backIndex
	 * TODO: Add caching to prevent repeated DOM node creation
	 */
	_generateBacklinks: function ($listElement, direction, backIndex) {
		var fragment = document.createDocumentFragment(),
			 $list = $listElement.clone(),
			 $backlink = null,
			 $backlinkAnchor = null;

		if (direction == "in") {
			dm.backlinksArray.push({
				"nodeRef": $listElement,
				"title": $listElement.find("a:first").text()
			});
		} else {
			dm.backlinksArray = dm.backlinksArray.slice(0, backIndex + 1);
		}

		$list
			 .find("a.active").removeClass(dm.config.linkActiveClass).end()
			 .find("a.has-submenu:first")
			 .removeClass("has-submenu").addClass(dm.config.linkActiveClass).end();

		/* TODO: rework to avoid for loop */
		for (var x = 0, len = dm.backlinksArray.length; x < len - 1; x++) {
			$backlink = dm.$backlinkHTML.clone();
			$backlink.find(".icon").text(dm.backlinksArray[x].title);
			$backlinkAnchor = $backlink.find("a");
			$backlinkAnchor
				 .data("title", dm.backlinksArray[x].title)
				 .data("index", x);
			if (dm.backlinksArray[x]["class"])
				$backlinkAnchor.addClass(dm.backlinksArray[x]["class"]);

			if (typeof dm.backlinksArray[x].nodeRef == 'string') {
				$backlinkAnchor.attr("href", dm.backlinksArray[x].nodeRef);
				$backlinkAnchor.data("nodeRef", dm.backlinksArray[x].nodeRef);
			} else
				$backlinkAnchor.data("nodeRef", dm.backlinksArray[x].nodeRef);

			fragment.appendChild($backlink[0])
		}

		$("ul:first", dm.$swipeInContainer).empty().append(fragment, $list);

	},

	/*
	 * Swipe between main categories and subcategories
	 * @param DOMelement (LI) listElement
	 * @param string direction
	 * @param string backIndex
	 * TODO: optimize slide animation
	 */
	_doSlide: function ($listElement, direction, backIndex) {

		var inDirection = (direction == 'in') ? 'right' : 'left',
			 outDirection = (direction == 'in') ? 'left' : 'right';

		dm._generateBacklinks($listElement, direction, backIndex);

		/* if clicked on back-to link for main list */
		if (dm.$categoriesContainer == $listElement) {
			dm.$swipeInContainer = dm.$categoriesContainer;
			$("> li a.active", dm.$categoriesContainer).removeClass(dm.config.linkActiveClass);
			$("> li.root-menu", dm.$categoriesContainer).next().find("a:first").addClass(dm.config.linkActiveClass);
			dm._populateList(new Array("summary"));
		}

		if (dm.$categoriesContainer.is(":visible")) {
			dm.$categoriesContainer.hide("slide", { direction: outDirection }, 150, function () {
				dm.$swipeInContainer.show("slide", { direction: inDirection }, 150);
				dm.$swipeOutContainer = dm.$swipeInContainer;
			})
		} else {
			dm.$swipeOutContainer.hide("slide", { direction: outDirection }, 150, function () {
				dm.$swipeInContainer.show("slide", { direction: inDirection }, 150);
				dm.$swipeInContainer = dm.$swipeOutContainer;
			})
		}
	},

	/*
	 * Scrolls to an list entry
	 * @param string entryId 	i.e. "a726"
	 */
	_scrollToEntry: function (entryId) {
		var $elem = $("#" + dm.config.section + "-list li[data-href='" + entryId + "']");

		if ($elem.length >= 1) {
			$elem.toggleClass("showing");
			Core.scrollTo($elem, 1000);
		}
	},

	/*
	 * Load category data via JSON and cache
	 * @param int catId
	 * @param int entryId
	 */
	_populateList: function (categoryObj, entryId) {

		var isParentCategory = (categoryObj.length == 1),
			 categoryId = (isParentCategory) ? categoryObj[0] : categoryObj[categoryObj.length - 1],
			 categoryEnc = Core.escapeSelector(categoryId),
			 url = Profile.url + "/" + categoryId,
			 categoryExists = $("#cat-" + categoryEnc).length > 0,
			 isOverview = (categoryId == 'summary');

			if(dm.config.section == "statistic") {
			sh.resetSearch(DynamicMenu.cache.filtering);
		} else {
			ah.resetSearch(DynamicMenu.cache.filtering);
		}
			DynamicMenu.cache.filtering = null;


		dm.cache.activeCategoryId = categoryEnc;

		$(".container, .table").hide();

		if (categoryExists) {

			/* statistics */
			if (isParentCategory && !isOverview)
				$("#cat-" + categoryEnc).parent().find(".table").show();

			$("#cat-" + categoryEnc).show();
			$("#search-container").show();

			if (entryId)
				dm._scrollToEntry(entryId)

		} else {

			$("#" + dm.config.section + "-list ").addClass("loading");

			setTimeout(function () {
				$.ajax({
					url: url,
					success: function (data) {
						$("#" + dm.config.section + "-list").removeClass("loading").append(data);
						$("#" + dm.config.section + "-list #cat-" + categoryEnc).show();
						$("#search-container, #" + dm.config.section + "-list > div:last").fadeIn("fast");

						if (entryId)
							dm._scrollToEntry(entryId)
					},
					dataType: "html"
				});
			}, 300)
		}

		if (isOverview && dm.config.section == 'achievement')
			$("#search-container").hide();

		$("#" + dm.config.section + "-search").val("");

		$('#search-container')
			.find('.view').show().end()
			.find('.reset').hide();

		Input.reset("#" + dm.config.section + "-search");
	},

	/*
	 * Handles styles and content loading for cross linking
	 */
	openEntry: function (direct) {
		var url = window.location.hash;

		//find link in navigation, do highlighting and list loading
		var hash = DynamicMenu._parseCategoryFromURL(url),
		 $categoryLink = $("#profile-sidebar-menu a[href*='" + DynamicMenu.config.section + "#" + hash[0] + "']:first"),
		 hasAnchor = hash[hash.length - 1].indexOf("a") != -1,
		 requestedCategory = new Array((hasAnchor) ? hash[hash.length - 2] : hash[hash.length - 1]);

		if ($categoryLink.hasClass("has-submenu") && !direct)
			DynamicMenu._doSlide($categoryLink.parent(), "in");

		DynamicMenu._populateList(requestedCategory, window.location.hash);

		var container = (requestedCategory == 92 || requestedCategory == 81 || requestedCategory == 15165 || requestedCategory == 15088 || requestedCategory == 15077 || requestedCategory == 15080 || requestedCategory == 15089 || requestedCategory == 15093) ? 'profile-sidebar-menu': 'swipe-in-container';

		$("#" + container + " .active").removeClass(dm.config.linkActiveClass);

		$highlightLink = (hash[1] && hash[1].indexOf("a") == -1) ?
			 $("#" + container + " a[href*='" + DynamicMenu.config.section + "#" + hash[0] + ":" + hash[1] + "']:first") :
			 $("#" + container + " a[href*='" + DynamicMenu.config.section + "#" + hash[0] + "']:first");

		$highlightLink.addClass(dm.config.linkActiveClass)
	}
};

var dm = DynamicMenu;

var Profile = {

	url: '',

	init: function() {
		$('#profile-sidebar-menu').delegate('li.disabled a.vault', 'mouseover', function() {

			var $this = $(this);
			var message;

				if(location.href.indexOf('/character/') != -1) { // Character profile
					message = MsgProfile.tooltip.vault.character;
				} else if(location.href.indexOf('/guild/') != -1) { // Guild profile
					message = MsgProfile.tooltip.vault.guild;
				}

			if(message) {
				Tooltip.show(this, Wow.createSimpleTooltip($this.text(), message));
			}
		});

		$('#profile-sidebar-menu').delegate('li.disabled a.coming-soon', 'mouseover', function() {

			var $this = $(this);
			var message = MsgProfile.tooltip.feature.notYetAvailable;

			if(message) {
				Tooltip.show(this, Wow.createSimpleTooltip($this.text(), message));
			}
		});

		$('#profile-info-realm').mouseover(function() {
			var $this = $(this);
			var battlegroup = $this.attr('data-battlegroup');
			if(battlegroup) {
				Tooltip.show(this, Wow.createSimpleTooltip($this.text(), battlegroup));
			}
		});

		$('.keyword')
			.find('.reset').click(Profile.keywordReset).end()
			.find('input').keyup(Profile.keywordClick);

		// Catch failed image downloads for character renders
		var regBGimage = /^\s*url\(("|'|)(.*?)\1\)\s*$/;
		var regAlt = /\?alt=([^&]+)/;
		$(".profile-wrapper, .profile-sidebar-character-model, #header .user-plate .avatar-wow").each(function(){
			var $elem = $(this);
			var url = $elem.css("background-image");
			var match = regBGimage.exec( url );
			if(!match){ return; }
			else{ url = match[2]; }
			var img = new Image();
			img.onerror = function(){
				var match = regAlt.exec(url);
				if(match){
					var fallback = match[1];
					$elem.css("background-image","url("+fallback+")");
				}
			}
			img.src = url;
		});

	},

	keywordClick: function() {
		var node = $(this),
			view = node.siblings('.view'),
			reset = node.siblings('.reset');

		if (node.val() != '') {
			view.hide();
			reset.show();
		} else {
			view.show();
			reset.hide();
	}
	},

	keywordReset: function() {
		var node = $(this),
			view = node.siblings('.view'),
			input = node.siblings('input');

		view.show();
		node.hide();
		input.val('').trigger('keyup').trigger('blur');
	}

};

$(document).ready(Profile.init);