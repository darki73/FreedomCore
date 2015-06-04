"use strict";

/**
 * Turns a background sprite image into a draggable and rotatable 3D model viewer.
 *
 * Initialize using ModelRotator.factory();
 */
var ModelRotator = {

	/**
	 * DOM element objects.
	 */
	node: null,
	viewer: null,
	document: null,
	zoomButton: null,
	rotateButton: null,

	/**
	 * Timer for automatic rotation.
	 */
	timer: null,

	/**
	 * Is the model currently being dragged or rotated.
	 */
	rotating: false,
	dragging: false,

	/**
	 * Is this being used on a touch screen device?
	 */
	isTouch: /(iphone|ipad|ipod|android)/.exec(navigator.userAgent.toLowerCase()),

	/**
	 * Current state of the model.
	 */
	frame: 0,
	lastFrame: 0,
	offset: null,
	lastOffset: null,
	coords: null,
	xPosition: 0,
	yPosition: 0,
	canDrag: false,

	/**
	 * Custom configuration.
	 */
	config: {},

	/**
	 * Overwrites for specific keys.
	 *
	 * frame width, number of frames per sequence, xoffset, yoffset
	 */
	overwrites: {},

	/**
	 * Factory method for generating ModelRotator instances.
	 *
	 * @param node
	 * @param config
	 */
	factory: function(node, config) {
		var instance = Object.create(ModelRotator);
			instance.init(node, config);

		return instance;
	},

	/**
	 * Create draggable object.
	 *
	 * @constructor
	 * @param node
	 * @param config
	 */
	init: function(node, config) {
		node = $(node);

		if (!node.length) {
			return;
		}

		// Merge configuration
		this.config = $.extend({}, {
			key: '',
			frameWidth: 280,
			frameHeight: 280,
			sequenceWidth: 6720,
			totalFrames: 24,
			velocity: 10,
			rotateTimer: 100,
			yOffset: 0,
			xOffset: 0,
			viewerClass: '.viewer',
			rotate: true,
			rotateClass: '.rotate',
			zoom: true,
			zoomClass: '.zoom',
			zoomCallback: function(value) {
				return value.replace('rotate', 'zoom');
			},
			dragCallback: null,
			invert: true
		}, config);

		var cfg = this.config;

		// Grab objects
		this.node = node;
		this.viewer = node.find(cfg.viewerClass);
		this.document = $(document);
		this.zoomButton = node.find(cfg.zoomClass);
		this.rotateButton = node.find(cfg.rotateClass);

		// If touch device
		if (this.isTouch) {
			this.node.addClass('touch');
		}

		// Setup event binds
		if (cfg.rotate && this.rotateButton.length) {
			this.rotateButton.bind('click', $.proxy(this.rotate, this));
		} else {
			this.rotateButton.remove();
		}

		if (cfg.zoom && this.zoomButton.length) {
			this.zoomButton.bind('click', $.proxy(this.zoom, this));
		} else {
			this.zoomButton.remove();
		}

		if (this.viewer.length) {
			this.viewer.bind((this.isTouch ? 'touchstart' : 'mousedown'), $.proxy(this.down, this));
			this.document.bind((this.isTouch ? 'touchend' : 'mouseup'), $.proxy(this.up, this));
			this.document.bind((this.isTouch ? 'touchmove' : 'mousemove'), $.proxy(this.move, this));

			// Prevent text selection in IE
			if (Core.isIE()) {
				this.viewer[0].onselectstart = function () { return false; };
			}

			// Update background image if it's IE6
			if (Core.isIE(6)) {
				this.viewer[0].style.backgroundImage = this.viewer[0].style.backgroundImage.replace(".png", "-ie6.png");
			}
		}

		// Overwrites?
		var map = this.overwrites;

		if (cfg.key && map[cfg.key]) {
			cfg.frameWidth 		= map[cfg.key][0];
			cfg.frameHeight		= map[cfg.key][0];
			cfg.totalFrames 	= map[cfg.key][1];
			cfg.sequenceWidth 	= map[cfg.key][0] * map[cfg.key][1];
			cfg.xOffset 		= map[cfg.key][2] || 0;
			cfg.yOffset 		= map[cfg.key][3] || 0;
		}

		// Get frame position (switch for older IE that don't support background-position)
		var bgPos = this.viewer.css( ( Core.isIE(6) || Core.isIE(7) || Core.isIE(8) ) ? 'background-position-x' : 'background-position' );
			bgPos = bgPos.replace('px', '').replace('%', '').replace('-', '').split(' ')[0];

		this.frame = this.lastFrame = Math.round(bgPos / cfg.frameWidth);

		// Verify image
		this.verifyImage();
	},

	/**
	 * Get background image (that works in all browsers).
	 *
	 * @return string
	 */
	backgroundImage: function() {
		var url = this.viewer.css('background-image');
			url = url.replace(/^url\(('|")?/, ''); // Remove leading url("
			url = url.replace(/('|")?\)$/, '');    // Remove trailing ")

		return url;
	},

	/**
	 * Triggered when mouse is pressed, initiates drag.
	 *
	 * @param e
	 */
	down: function(e) {
		if (!this.isTouch && e.which !== 1 || !this.canDrag) {
			return false;
		}

		// Disable auto-rotation
		if (this.rotating) {
			this.stop();
		}

		// We are now dragging!
		this.dragging = true;
		this.node.addClass('dragging');

		if (this.config.dragCallback) {
			this.config.dragCallback(true);
		}

		// Save mouse pointer coords on dragstart
		if (this.coords === null) {
			this.coords = this.mouseCoords(e);
		}

		return false;
	},

	/**
	 * Is it currently dragging.
	 *
	 * @return boolean
	 */
	isDragging: function() {
		return this.dragging;
	},

	/**
	 * Is it currently rotating.
	 *
	 * @return boolean
	 */
	isRotating: function() {
		return this.rotating;
	},

	/**
	 * Calculate mouse offset from passed in source coord.
	 *
	 * @param e
	 * @param coords
	 * @return object
	 */
	mouseOffset: function(e, coords) {
		var mousePos = this.mouseCoords(e);

		return {
			x: mousePos.x - coords.x,
			y: mousePos.y - coords.y
		};
	},

	/**
	 * Calculate mouse coordinates.
	 *
	 * @param e
	 * @return object
	 */
	mouseCoords: function(e) {
		if (this.isTouch) {
			e = e.touches[0];
		}

		if (e.pageX && e.pageY) {
			return {
				x: e.pageX,
				y: e.pageY
			};
		}

		return {
			x: e.clientX + (document.body.scrollLeft - document.body.clientLeft),
			y: e.clientY + (document.body.scrollTop  - document.body.clientTop)
		};
	},

	/**
	 * Executes a timer that handles the rotation of the model.
	 *
	 * @param e
	 */
	move: function(e) {
		if (!this.dragging || this.rotating || this.timer || !this.canDrag) {
			return false;
		}

		// Fake a timer being used
		this.timer = true;

		// On mousemove calculate difference to dragstart coords
		this.offset = this.mouseOffset(e, this.coords);

		// Start drag rotation
		this._drag();

		return true;
	},

	/**
	 * Reset the viewer to defaults.
	 */
	reset: function() {
		this.node.removeClass('load-fail can-drag');

		this.stop();
		this.dragging = false;
		this.canDrag = false;
		this.frame = 0;
		this.lastFrame = 0;
		this.offset = null;
		this.lastOffset = null;
		this.coords = null;
		this.xPosition = 0;
		this.yPosition = 0;
	},

	/**
	 * Start or stop the automatic rotation.
	 *
	 * @param e
	 */
	rotate: function(e) {
		if (e) {
			e.stopPropagation();
			e.preventDefault();
		}

		if (this.timer !== null) {
			this.stop();
		} else {
			this.timer = setInterval($.proxy(this._rotate, this), this.config.rotateTimer);
			this.rotating = true;
			this.node.addClass('rotating');
		}
	},

	/**
	 * Set the drag callback.
	 *
	 * @param callback
	 */
	setDragCallback: function(callback) {
		if (Core.isCallback(callback))
			this.config.dragCallback = callback;
	},

	/**
	 * Set the image to use (for changing dynamically).
	 *
	 * @param src
	 */
	setImage: function(src) {
		this.reset();

		this.viewer.css({
			backgroundPosition: '0 0',
			backgroundImage: "url('" + src + "')"
		});

		this.verifyImage();
	},

	/**
	 * Stop the rotation.
	 */
	stop: function() {
		if (this.rotating) {
			clearInterval(this.timer);

			this.timer = null;
			this.rotating = false;
			this.node.removeClass('rotating');
		}
	},

	/**
	 * Triggered when mouse is released, disables drag and resets.
	 */
	up: function() {
		if (this.rotating || !this.canDrag) {
			return false;
		}

		this.lastFrame = this.frame;
		this.coords = null;

		// No longer dragging
		this.dragging = false;
		this.node.removeClass('dragging');

		if (this.config.dragCallback) {
			this.config.dragCallback(false);
		}

		return true;
	},

	/**
	 * Verify the image has loaded, else throw errors.
	 */
	verifyImage: function() {
		var path = this.viewer[0].style.backgroundImage || '';

		// Only verify if an image is actually set
		if (path.indexOf('.jpg') === -1 && path.indexOf('.png') === -1) {
			return;
		}

		var self = this,
			bgImg = new Image();

		bgImg.onerror = function() {
			self.node.addClass('load-fail');
		};

		bgImg.onload = function() {
			if (this.width > self.config.frameWidth) {
				self.node.addClass('can-drag');
				self.canDrag = true;
			}
		};

		bgImg.src = this.backgroundImage();
	},

	/**
	 * Zoom into an image by opening a lightbox.
	 *
	 * @param e
	 */
	zoom: function(e) {
		e.stopPropagation();
		e.preventDefault();

		Lightbox.loadImage([{
			src: this.config.zoomCallback(this.backgroundImage())
		}]);
	},

	/**
	 * Rotate through each frame using a timer.
	 */
	_rotate: function() {
		var config = this.config,
			frame = this.lastFrame + 1;

		if (frame >= config.totalFrames) {
			frame = 0;
		}

		var x = (frame * config.frameWidth) + config.xOffset,
			y = 0 + config.yOffset;

		// Save last coords for calculations
		this.frame = this.lastFrame = frame;
		this.xPosition = x;
		this.yPosition = y;

		// Set position
		this.viewer[0].style.backgroundPosition = '-' + x + 'px ' + y + 'px';
	},

	/**
	 * Drag the model horizontally and save the mouse coordinates and frame status.
	 */
	_drag: function() {
		var config = this.config;

		// Calculate how many frames should show depending on distance/velocity
		var lastFrame = this.lastFrame || 0,
			goToFrame;

		// If we inverting rotation, go opposite direction
		if (config.invert) {
			goToFrame = lastFrame + -Math.round(this.offset.x / config.velocity);
		} else {
			goToFrame = lastFrame + Math.round(this.offset.x / config.velocity);
		}

		// Is frame within range
		if (goToFrame >= config.totalFrames || goToFrame < 0) {
			goToFrame = goToFrame - (config.totalFrames * Math.floor(goToFrame / config.totalFrames));
		}

		// Calculate new X background position based on current X background position
		var x = (goToFrame * config.frameWidth) + config.xOffset,
			y = 0 + config.yOffset;

		// Set div's X background position to 0 if new X background postion exceeds image width
		if (x > config.sequenceWidth || x < -config.sequenceWidth) {
			x = 0 + config.xOffset;
		}

		// Save last coords for calculations
		this.frame = goToFrame;
		this.lastOffset = this.offset;
		this.xPosition = x;
		this.yPosition = y;
		this.timer = null;

		// Set position
		this.viewer[0].style.backgroundPosition = '-' + x + 'px ' + y + 'px';
	}

};