"use strict";

/**
 * Opens a lightbox over the content which can display images and videos.
 */
var Lightbox = {

    timeout:		0,
    initialized:	false,
    contents:	   [], //list of images or videos
    currentIndex:   0, //used for paging if content.length > 1
    contentType:	"image",
    DEFAULT_WIDTH:  480,
    DEFAULT_HEIGHT: 360,
    anchorClose:	true,

    // default config
    config: {
        showTitle: false,
        includeControls: false
    },

    /**
     * Initializes lightbox and caches relevant DOM elements
     */
    init: function() {
        //init blackout first (adds to DOM)
        Blackout.initialize();

        //build lightbox elements (adds to DOM)
        Lightbox.build();

        Lightbox.initialized = true;
    },

    /**
     * Store content data for use later
     *
     * @param object content - array of images or videos
     * @param string contentType - type of content being show in the ligthbox, either "image", "video" or "embed"
     */
    storeContentData: function(content, contentType) {
        if (!Lightbox.initialized) {
            Lightbox.init();
        }

        //store image list for paging
        Lightbox.contents = content;

        Lightbox.contentType = contentType;

        //store current index
        Lightbox.currentIndex = 0;

        //disable/enable paging
        Lightbox.controls.toggleClass("no-paging", (content.length < 2));
    },

    /**
     * Loads image into lightbox, adds paging if necessary
     *
     * @param array images - array of objects containing title (optional), src, and path (optional) of image to view.
     *  Example:
     *	  [{ title: "Image title",
     *		src:	"http://us.media.blizzard.com/sc2/media/screenshots/protoss_archon_002-large.jpg",
     *		path:   "/sc2/en/media/screenshots/?view=protoss_archon_002" (omitting the path property will cause the gallery-view icon to hide)
     *	  }]
     *
     */
    loadImage: function(images, dontStore, showIndex) {
        if (!Lightbox.initialized) {
            Lightbox.init();
        }

        //store data
        if (!dontStore) {
            Lightbox.storeContentData(images, "image");
        }

        var index = showIndex || ((typeof images === 'number') ? images : 0);

        //show loading anim and start image fetch
        if (Lightbox.contents[index].src !== "") {
            Lightbox.currentIndex = index;
            Lightbox.setFrameDimensions(Lightbox.DEFAULT_WIDTH, Lightbox.DEFAULT_HEIGHT);

            Lightbox.content.removeAttr("style").addClass("loading").removeClass("lightbox-error").html("");

            Lightbox.show();
            Lightbox.setImage(Lightbox.contents[index]);
        } else {
            Lightbox.error();
        }
    },

    /**
     * Checks image until its loaded then sets as background image
     */
    setImage: function(image) {
        if (Core.isIE(6)) {
            if (Lightbox.controls.hasClass("no-paging") && Lightbox.controls.hasClass("no-gallery")) {
                Lightbox.controls.addClass("no-controls").removeClass("no-paging no-gallery");
            }
        }

        // Preload image
        Lightbox.preloadImage(image,function(loadedImage){

            Lightbox.emptyContent();

            Lightbox.setFrameDimensions(loadedImage.width, loadedImage.height);
            Lightbox.content.html(loadedImage);

            // Update title if supplied
            if(Lightbox.config.showTitle){
                Lightbox.title.html(image.title || "");
            }
        });
    },

    /**
     * Loads a video or set of videos with paging in the lightbox
     *
     * @param arrray videos - array of video data
     *
     *  Example:
     *	  [{  title:       "Video Title Text", //optional
     *		  width:	   890,
     *		  height:	  500,
     *		  flvPath:	 '/what-is-sc2/what-is-sc2.flv',
     *		  path:		'/sc2/en/media/videos#/what-is-sc2' //optional
     *		  showRating:  true, //optional, defaults to true
     *		  cachePlayer: false //optional, defaults to false
     *	  }];
     */
    loadVideo: function(videos, dontStore, showIndex) {
        if (!Lightbox.initialized) {
            Lightbox.init();
        }

        //store data
        if(!dontStore){
            Lightbox.storeContentData(videos, "video");
        }

        var index = showIndex || ((typeof videos === 'number') ? videos : 0);

        //set first video
        Lightbox.setVideo(videos[index]);
    },

    /**
     * Sets video in lightbox
     */
    setVideo: function(video) {
        var currentFlashVars = {
            flvPath:   (video.flvBase || Flash.videoBase) + video.flvPath,
            flvWidth:  video.width,
            flvHeight:	video.height,
            ratingPath: video.customRating,
            captionsPath:	  "",
            captionsDefaultOn: (Core.locale !== "en-us" && Core.locale !== "en-gb")
        };

        //add rating values
        if (!video.showRating || false) {
            currentFlashVars = $.extend(Flash.defaultVideoFlashVars, currentFlashVars);
        }

        //generate no cache
        var noCache = new Date();
        noCache = "?nocache=" + noCache.getTime();

        //add captions
        if (typeof video.captionsPath !== "undefined" && video.captionsPath !== "") {
            currentFlashVars.captionsPath = video.captionsPath;
        } else {
            delete currentFlashVars.captionsPath;
        }

        //change rating if needed
        if (typeof video.customRating !== "undefined" && video.customRating !== "") {
            if (video.customRating.indexOf("NONE") > -1) {
                delete currentFlashVars.ratingPath;
            } else {
                currentFlashVars.ratingPath = video.customRating;
            }
        } else {
            currentFlashVars.ratingPath = Flash.ratingImage;
        }

        //create a target for the video
        Lightbox.emptyContent();
        $("<div id='flash-target' />").appendTo(Lightbox.content);

        swfobject.embedSWF(Flash.videoPlayer + noCache, "flash-target", video.width, video.height, Flash.requiredVersion, Flash.expressInstall, currentFlashVars, Flash.defaultVideoParams, {}, Lightbox.flashEmbedCallback);

        // Update title if supplied
        if(Lightbox.config.showTitle){
            Lightbox.title.html(video.title || "");
        }

        Lightbox.setFrameDimensions(video.width, video.height);
        Lightbox.show();
    },

    /**
     * Loads an embedded video or set of videos with paging into the lightbox
     */
    loadEmbed: function(embeds, dontStore, showIndex){

        if (!Lightbox.initialized) {
            Lightbox.init();
        }

        //store data
        if(!dontStore){
            Lightbox.storeContentData(embeds, "embed");
        }

        var index = showIndex || ((typeof embeds === 'number') ? embeds : 0);

        //set first video
        Lightbox.setEmbed(embeds[index]);

    },

    /**
     * Sets embedded video in lightbox
     */
    setEmbed: function(embed){

        //create a target for the video
        Lightbox.emptyContent();

        $("<object width='"+embed.width+"' height='"+embed.height+"'>" +
            "<param name='movie' value='https://www.youtube.com/v/"+embed.src+"?autoplay=1'/>" +
            "<param name='allowFullScreen' value='true' />" +
            "<param name='allowScriptAccess' value='always'/>" +
            "<param name='wmode' value='opaque' />" +
            "<embed src='https://www.youtube.com/v/"+embed.src+"?autoplay=1' type='application/x-shockwave-flash' allowfullscreen='true' allowScriptAccess='always' wmode='opaque' width='"+embed.width+"' height='"+embed.height+"'/>" +
            "</object>").appendTo(Lightbox.content);

        // Update title if supplied
        if(Lightbox.config.showTitle){
            Lightbox.title.html(embed.title || "");
        }

        Lightbox.setFrameDimensions(embed.width, embed.height);
        Lightbox.show();

    },

    /**
     * View image in the media gallery
     */
    viewInGallery: function() {
        Tooltip.hide();

        Core.goTo(Lightbox.contents[Lightbox.currentIndex].path);

        return false;
    },

    /**
     * Dynamically sets border widths/heights based on dimensions so style integrity is maintained
     */
    setFrameDimensions: function(width, height) {
        if (width === 0 || height === 0) {
            Lightbox.error();
        } else {

            // Explicitly set content container to content height
            Lightbox.content.css({
                height: height + "px"
            });

            // Add title height if it exists
            height = (Lightbox.config.showTitle) ? height + Lightbox.title.height() : height;

            // Add control height if they arent an overlay
            height = (Lightbox.config.includeControls) ? height + Lightbox.controls.height() : height;

            Lightbox.container.css({
                top:	Page.scroll.top + "px",
                width:  width + "px",
                height: height + "px"
            });

            Lightbox.borderTop.width(width - 10 + "px");
            Lightbox.borderbottom.width(width - 12 + "px");
            Lightbox.borderRight.height(height - 9 + "px");
            Lightbox.borderLeft.height(height - 9 + "px");
        }
    },

    /**
     * Toggles class on controls depending on if there is a link to the media gallery
     *
     * @param object content
     */
    checkGalleryLinkDisplay: function(hasPath) {
        Lightbox.controls.toggleClass("no-gallery", hasPath);
    },

    /**
     * Starts image preload
     */
    preloadImage: function(loadingImage,callback) {

        var tempImage = new Image();

        $(tempImage).load(function(){
            callback(tempImage);
        });

        tempImage.src = loadingImage.src;
    },

    /**
     * Show the lightbox.
     */
    show: function() {
        Blackout.show(function() {
            Lightbox.container[0].style.display = "block";
            Lightbox.checkGalleryLinkDisplay(!(Lightbox.contents[Lightbox.currentIndex].path));
        }, Lightbox.close);
    },

    /**
     * Hides the lightbox
     */
    close: function() {
        clearTimeout(Lightbox.timeout);

        Blackout.hide(Lightbox.container.hide());

        //unload swf if needed
        if (Lightbox.contentType === "video") {
            swfobject.removeSWF("flash-target");
        }

        if(Lightbox.contentType === "embed") {
            Lightbox.emptyContent();
        }

        //hide tooltip to prevent artifacts
        Tooltip.hide();
    },

    /**
     * Clears the content/classes of the viewer, putting it back into a fresh state
     */
    emptyContent: function() {
        Lightbox.content.removeAttr("style").removeClass("loading lightbox-error").empty();
    },

    /**
     * Shows lightbox in error state
     */
    error: function() {
        Lightbox.emptyContent();

        Lightbox.setFrameDimensions(Lightbox.DEFAULT_WIDTH, Lightbox.DEFAULT_HEIGHT);

        Lightbox.content.addClass("lightbox-error").html("Error loading content.");

        Lightbox.show();
    },

    /**
     * Builds lightbox elements on demand so they aren't in DOM until we need them
     */
    build: function() {
        Lightbox.anchor = $('<div id="lightbox-anchor" />').click(function() {
            if (Lightbox.anchorClose) {
                Lightbox.close();
            }
        });

        Lightbox.container = $('<div id="lightbox-container" />')
            .mouseover(function() {
                Lightbox.anchorClose = false
            }).mouseout(function() {
                Lightbox.anchorClose = true
            }).appendTo(Lightbox.anchor);

        Lightbox.title = $('<h1 id="lightbox-title" />')
            .mouseover(function() {
                Lightbox.anchorClose = false
            }).mouseout(function() {
                Lightbox.anchorClose = true
            }).appendTo(Lightbox.container);

        Lightbox.content = $('<div id="lightbox-content"/>')
            .mouseover(function() {
                Lightbox.anchorClose = false
            }).mouseout(function() {
                Lightbox.anchorClose = true
            }).appendTo(Lightbox.container).click(Lightbox.next);

        //ui-element link element template
        var uiElementLink = $("<a />").addClass("ui-element").attr("href", "javascript:;");

        //build controls
        Lightbox.controls = $('<div class="control-wrapper no-paging no-gallery" />');
        Lightbox.controls
            .append(
            $('<div class="lightbox-controls ui-element" />')
                //previous
                .append(uiElementLink.clone().addClass("previous").click(Lightbox.previous))
                //next
                .append(uiElementLink.clone().addClass("next").click(Lightbox.next))
                //gallery view
                .append(uiElementLink.clone().addClass("gallery-view").click(Lightbox.viewInGallery)
                .mouseover(function() {
                    Tooltip.instances.common.show(this, Msg.ui.viewInGallery);
                    Tooltip.instances.common.wrapper.css("z-index", "9007");
                })
                .mouseout(function() {
                    Tooltip.instances.common.wrapper.css("z-index", "1000")
                }))
        );

        //create borders before appending (need to access borders later to resize
        var border = $("<div />").addClass("border");
        Lightbox.borderTop    = border.clone().attr("id", "lb-border-top");
        Lightbox.borderRight  = border.clone().attr("id", "lb-border-right");
        Lightbox.borderbottom = border.clone().attr("id", "lb-border-bottom");
        Lightbox.borderLeft   = border.clone().attr("id", "lb-border-left");

        //plain corner element to clone
        var corner = $("<div />").addClass("corner");

        //append everything
        Lightbox.container
            //add corners
            .append(corner.clone().addClass("corner-top-left"))
            .append(corner.clone().addClass("corner-top-right"))
            .append(corner.clone().addClass("corner-bottom-left"))
            .append(corner.clone().addClass("corner-bottom-right"))
            //add borders
            .append(Lightbox.borderTop)
            .append(Lightbox.borderRight)
            .append(Lightbox.borderbottom)
            .append(Lightbox.borderLeft)
            //paging controls
            .append(Lightbox.controls)
            //close button
            .append(uiElementLink.clone().addClass("lightbox-close").click(Lightbox.close));

        //append to body at end to avoid any redraws
        Lightbox.anchor.appendTo("body");

        // toggle so IE will load images properly
        if (Core.isIE(6)) {
            Lightbox.content.show().hide();
        }
    },

    /**
     * Gets the next image
     */
    next: function() {
        var totalContent = Lightbox.contents.length;

        if (totalContent > 1) {
            //increment index
            Lightbox.currentIndex++;

            //wrap back to 0
            if (Lightbox.currentIndex >= totalContent) {
                Lightbox.currentIndex = 0;
            }

            if (Lightbox.contentType === "image") {
                Lightbox.setImage(Lightbox.contents[Lightbox.currentIndex]);
            } else if (Lightbox.contentType === "video") {
                Lightbox.setVideo(Lightbox.contents[Lightbox.currentIndex]);
            } else if (Lightbox.contentType === "embed") {
                Lightbox.setEmbed(Lightbox.contents[Lightbox.currentIndex])
            }
        }
    },

    /**
     * Gets the previous image
     */
    previous: function() {
        var totalContent = Lightbox.contents.length;

        if (totalContent > 1) {
            //decrement
            Lightbox.currentIndex--;

            if (Lightbox.currentIndex < 0) {
                Lightbox.currentIndex = Lightbox.contents.length - 1;
            }

            if (Lightbox.contentType === "image") {
                Lightbox.setImage(Lightbox.contents[Lightbox.currentIndex]);
            } else if (Lightbox.contentType === "video") {
                Lightbox.setVideo(Lightbox.contents[Lightbox.currentIndex]);
            } else if (Lightbox.contentType === "embed") {
                Lightbox.setEmbed(Lightbox.contents[Lightbox.currentIndex]);
            }
        }
    },

    /**
     * Display error when flash is not installed
     */
    flashEmbedCallback: function(e) {
        if (!e.success) {
            //show flash not installed error messages
            Lightbox.content.html(Flash.getFlashError());
        }
    }
};