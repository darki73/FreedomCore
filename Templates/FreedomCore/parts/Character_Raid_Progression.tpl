<script type="text/javascript">
    //<![CDATA[
    $(document).ready(function() {
        new Summary.RaidProgression({ nTrivialRaids: 0, nOptimalRaids: 0, nChallengingRaids: 5  }, {
            {foreach $Raids.Classic as $ClassicRaids}
                {$ClassicRaids.data.instance}: {
                    name: "{$ClassicRaids.data.instance}",
                    playerLevel: {$ClassicRaids.data.npcs.0.instance_level},
                    nPlayers: -10,
                    location: "[WIP]",
                    expansion: {$ClassicRaids.data.expansion},
                    heroicMode: false,
                    mythicMode: false,
                    bosses:
                    [
                        { name: "{$ClassicRaids.data.npcs.0.name}", nKills: {$ClassicRaids.data.counter } }
                    ]
                },
            {/foreach}

            {foreach $Raids.TBC as $TBCRaids}
            {$TBCRaids.data.instance}: {
                name: "{$TBCRaids.data.instance}",
                playerLevel: {$TBCRaids.data.npcs.0.instance_level},
                nPlayers: -10,
                location: "[WIP]",
                expansion: {$TBCRaids.data.expansion},
                heroicMode: false,
                mythicMode: false,
                bosses:
                [
                    { name: "{$TBCRaids.data.npcs.0.name}", nKills: {$TBCRaids.data.counter } }
                ]
            },
            {/foreach}

            {foreach $Raids.WotLK as $WotLKRaids}
                {$WotLKRaids.data.instance}: {
                    name: "{$WotLKRaids.data.instance}",
                    playerLevel: {$WotLKRaids.data.npcs.0.instance_level},
                    nPlayers: -10,
                    location: "[WIP]",
                    expansion: {$WotLKRaids.data.expansion},
                    heroicMode: false,
                    mythicMode: false,
                    bosses:
                    [
                        {if isset($WotLKRaids.data.counter)}
                            { name: "{$WotLKRaids.data.npcs.0.name}", nKills: {$WotLKRaids.data.counter} }
                        {else}
                            {foreach $WotLKRaids.data.npcs as $NPC}
                                { name: "{$NPC.name}", nKills: {$NPC.counter}, nHeroicKills: 0 },
                            {/foreach}
                        {/if}
                    ]
                },
            {/foreach}

        });
    });
    //]]>
</script>
