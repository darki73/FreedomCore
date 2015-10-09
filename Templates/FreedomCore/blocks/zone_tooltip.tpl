<div class="wiki-tooltip">
    <span class="icon-frame frame-36 zone-thumbnail thumb-{$Zone.link_name}"></span>
    <h3>
		<span class="float-right color-q0">
            {if $Zone.min_level == $Zone.max_level}
                {#Zones_Level#} {$Zone.min_level} {if $Zone.heroic_level_required != 0} ({$Zone.heroic_level_required}<span class="icon-heroic-skull"/>){/if}
            {else}
                {#Zones_Level#} {$Zone.min_level}–{$Zone.max_level} {if $Zone.heroic_level_required != 0} ({$Zone.heroic_level_required}<span class="icon-heroic-skull"/>){/if}
            {/if}

        </span>
        {$Zone.name}
    </h3>
    <span class="expansion-name color-ex{$Zone.expansion_required.expansion}">
        {#Expansion_Required#} {$Zone.expansion_required.translation}
    </span>
    {if $Zone.heroic_level_required == 85}
        <br />
        <span class="expansion-name color-ex3">
            {#Zones_Heroic_Cata#}
        </span>
    {elseif $Zone.heroic_level_required == 90}
        <br />
        <span class="expansion-name color-ex4">
            {#Zones_Heroic_MoP#}
        </span>
    {/if}

    <div class="color-tooltip-yellow">
        {$Zone.tooltip_description}
    </div>
    <ul class="item-specs">
        <li>
            <span class="color-tooltip-yellow">{#Type#}:</span>
            {$Zone.instance_type.translation}
            {if $Zone.heroic_possible}
            ({#Zones_Heroic#})
            <span class="icon-heroic-skull"></span>
            {/if}
        </li>
        <li>
            <span class="color-tooltip-yellow">{#Zones_Place#}:</span>
            {$Zone.zone_name}
        </li>
    </ul>
</div>