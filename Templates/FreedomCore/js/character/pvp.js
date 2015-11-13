
var Pvp = {

    calculator: $('#conquest-calculator'),

    /**
     * Load selected based on hash.
     */
    initialize: function() {
        var defaultToFirstTab = true,
            hash = Core.getHash(),
            hashEnc = Core.escapeSelector(hash);

        if(hash) {
            if(Pvp.showTeam(hash, $('#pvp-tab-'+ hashEnc))) {
                defaultToFirstTab = false;
            }
        }

        if(defaultToFirstTab) {
            var $firstActiveTab = $('#pvp-tabs div.tab:not(.tab-disabled):first');

            if($firstActiveTab.length > 0) {
                hash = $firstActiveTab.attr('data-id');
                Pvp.showTeam(hash, $('#pvp-tab-'+ hashEnc));
            }
        }

        // Event handlers
        $('#pvp-tabs a.team-name').click(function(event) {
            event.stopPropagation();
        });

        $('#pvp-tabs').delegate('div.tab', 'click', function() {
            var $this = $(this);
            var id =  $this.attr('data-id');

            Pvp.showTeam(id, this);
        });

        // Conquest calculator
        Pvp.calculator
            .find('input').keydown(function(e) {
                return KeyCode.isNumeric(e);
            }).end()
            .find('.close').click(Pvp.toggleCalculator);

    },

    /**
     * Display the selected team.
     *
     * @param id
     * @param node
     */
    showTeam: function(id, node) {
        var idEnc = Core.escapeSelector(id),
            nodeEnc = Core.escapeSelector(node),
            $content = $('#pvp-tab-content-'+ idEnc);

        if(!$content.length) {
            return false;
        }

        node = $(nodeEnc);

        $('#pvp-tabs div.tab').removeClass('tab-selected');
        $('#pvp-tabs-content div.tab-content').hide();

        node.addClass('tab-selected');

        $content.show();
        location.hash = id;

        return true;
    },

    toggleCalculator: function() {

        if(Pvp.calculator.is(':visible')) {
            Pvp.calculator.hide();
        } else {
            Pvp.calculator.show();
            setTimeout(function() { var e = Pvp.calculator.find('input:nth(0)'); e.focus(); e.select() }, 1);
        }
    },

    calculateConquestPoints: function() {
        var arenaRating = $('#arena-rating').val() || 0,
            bgRating = $('#bg-rating').val() || 0;

        $.ajax({
            url: Core.baseUrl +'/pvp/conquest-calculator',
            type: 'GET',
            data: {
                arenaRating: arenaRating,
                bgRating: bgRating
            },
            dataType: 'json',
            success: function(response) {
                $('#arena-cap').html(response.arenaCap);
                $('#bg-cap').html(response.bgCap);
            }
        })
    }

};