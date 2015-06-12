
$(function() {
    Npc.initialize();
});

var Npc = {

    /**
     * Collection of 3D model object instances.
     */
    models: {},

    /**
     * Collection of all npcs on this page.
     */
    npcs: {},

    /**
     * Total count of npcs.
     */
    total: 0,

    /**
     * The current faction.
     */
    faction: -1,

    /**
     * Initialize binds for boss tabs. Autoselect boss if hash exists.
     *
     * @constructor
     */
    initialize: function() {
        var bosses = $('#bosses'),
            radios = $('#model-buttons'),
            callback = function() {
                return Npc.view($(this).data('id'));
            };

        if (bosses.length)
            bosses.find('a').click(callback);

        if (radios.length)
            radios.find('a').click(callback);

        Filter.initialize(function(query) {
            if (query.npc) {
                var npc = Npc.find(query.npc, 'slug');

                if (npc.id)
                    Npc.view(npc.id);
            }
        });
    },

    /**
     * Find an npc based on id or slug.
     *
     * @param id
     * @param type
     */
    find: function(id, type) {
        if (type) {
            var npc;

            $.each(Npc.npcs, function() {
                if (this[type] == id)
                    npc = this;
            });

            return npc;
        }

        return Npc.npcs[id];
    },

    /**
     * Show the abilities and 3D model for the selected npc.
     *
     * @param id
     */
    view: function(id) {
        if (!id || !Npc.npcs[id])
            return false;

        var npc = Npc.npcs[id];

        Filter.addParam('npc', npc.slug);
        Filter.applyQuery();

        // Faction
        if (npc.faction)
            Npc.toggle(npc.faction, true);

        // Abilites
        $('#abilities ul').hide();
        $('#ability-'+ id).show();
        $('#bosses a').removeClass('boss-active');
        $('#boss-tab-'+ id).addClass('boss-active');

        // Models
        $('#wiki .sidebar .model').hide();
        $('#model-'+ id).show();
        $('#model-buttons a').removeClass('button-active');
        $('#model-button-'+ id).addClass('button-active');

        $.each(Npc.models, function() {
            this.stop();
        });

        return false;
    },

    /**
     * Toggle between horde and alliance factions.
     *
     * @param id
     * @param dontClick
     */
    toggle: function(id, dontClick) {
        if (Npc.faction == id)
            return;

        if (Npc.total == 0) {
            Npc.total = $("#model-buttons a").length;
        }

        if (Npc.total > 2) {
            $('#model-buttons, #bosses .npcs')
                .find('a').hide().end()
                .find('.side-'+ id).show();
        }

        if (!dontClick)
            $('#bosses .side-'+ id +':first').click();

        Npc.faction = id;

        $('#bosses .toggler')
            .find('button').removeClass('disabled').end()
            .find('.'+ (id == 1 ? 'horde' : 'alliance')).addClass('disabled');
    }

}