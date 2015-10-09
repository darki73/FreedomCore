
$(function() {
    Zone.initialize();
});

var Zone = {

    /**
     * Map thumbnail width.
     */
    mapWidth: 265,

    /**
     * Mapping of floor lightbox data.
     */
    floors: [],

    /**
     * Initialize the zone maps.
     */
    initialize: function() {
        Zone.viewFloor(1);

        $('#map-radios a').click(function() {
            return Zone.viewFloor($(this).data('id'));
        });
    },

    /**
     * Bind the onclick lightbox event.
     *
     * @param index
     */
    bindFloor: function(index) {
        $('#map-floors').unbind('click').bind('click', function() {
            Lightbox.storeContentData(Zone.floors, 'image');
            Lightbox.loadImage(index, true);

            return false;
        });
    },

    /**
     * View a floor by moving the background position and bind onclick.
     *
     * @param floor
     */
    viewFloor: function(floor) {
        Zone.bindFloor((floor - 1));

        $('#map-radios a').removeClass('radio-active');
        $('#map-radio-'+ floor).addClass('radio-active');
        $('#map-floors').css('background-position', '-'+ (Zone.mapWidth * (floor - 1)) +'px 0px');
    }

}