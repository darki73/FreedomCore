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

		doc.delegate('#comments #comments-sorting-tabs .menu-best a', 'click', function(e) {
			e.preventDefault();

			if (!$(this).hasClass('tab-active')) {
				Comments.switchSortOrder('best');
			}
		});
		doc.delegate('#comments #comments-sorting-tabs .menu-latest a', 'click', function(e) {
			e.preventDefault();

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
					Comments.showErrors(error, ['unknown']);
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
		$('<li/>').addClass('nested-reply').append(Comments.replyForm).insertAfter(target);

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
			url: Core.baseUrl + '/discussion/comment/' + id + '/delete.json',
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
		view = view || Core.getHash() || Cookie.read('discussion.sort') || Comments.defaultCommentSort;

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
				Comments.wrapper.addClass('comments-error')
					.find('.subheader-2').toggleClass('hide');
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
					node.find('.pull-' + (diff === 1 ? 'single' : 'multiple')).show().find('span').text(diff);
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

			$('.reply-button').attr("data-tooltip", Msg.cms.throttleError).addClass("disabled");

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
			$(this).find('li')
				.removeClass('current')
				.eq(page - 1).addClass('current');
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