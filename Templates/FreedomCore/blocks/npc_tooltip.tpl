<div class="wiki-tooltip">
    <span class="icon-frame frame-56" style="background-image: url(/Uploads/Core/NPC/Cache/creature{$NPC.entry}.jpg)"></span>
    <h3>
        {$NPC.name}
    </h3>
    <div class="color-q0">
        {$NPC.subname}
    </div>
    <div class="color-tooltip-yellow">

    </div>
    <ul class="item-specs">
        <li>
            <span class="color-tooltip-yellow">{#Zones_Level#}:</span>
            {assign 'CreatureMaxLevel' '0'}
            {assign 'CreatureMaxHP' '0'}
            {if $NPC.difficulty_entry_3 != 0}
                {$CreatureMaxLevel = $NPC.difficulty_entry_3.maxlevel}
                {$CreatureMaxHP = $NPC.difficulty_entry_3.health}
            {elseif $NPC.difficulty_entry_2 != 0}
                {$CreatureMaxLevel = $NPC.difficulty_entry_2.maxlevel}
                {$CreatureMaxHP = $NPC.difficulty_entry_2.health}
            {elseif $NPC.difficulty_entry_1 != 0}
                {$CreatureMaxLevel = $NPC.difficulty_entry_1.maxlevel}
                {$CreatureMaxHP = $NPC.difficulty_entry_1.health}
            {/if}
            {if $NPC.minlevel == 83}
                {$NPC.minlevel} {#NPC_Boss#}
            {else}
                {if $CreatureMaxLevel != 0}
                    {$NPC.minlevel}–{$CreatureMaxLevel}
                {else}
                    {$NPC.minlevel}
                {/if}
                {if $NPC.rank == 1}
                    {#NPC_Elite#}
                {/if}
                {if $NPC.difficulty_entry_1 != 0 || $NPC.difficulty_entry_2 != 0 || $NPC.difficulty_entry_3 != 0}
                    (<span class="color-tooltip-green">{$CreatureMaxLevel}</span> {#Zones_Heroic#})
                {/if}
            {/if}
        </li>
        <li>
            <span class="color-tooltip-yellow">{#NPC_Health#}:</span>
            {if $CreatureMaxHP != 0}
                {($NPC.health/1000)|string_format:"%.1f"}K–{($CreatureMaxHP/1000)|string_format:"%.1f"}K
            {else}
                {($NPC.health/1000)|string_format:"%.1f"}K
            {/if}
            {if $NPC.difficulty_entry_1 != 0 || $NPC.difficulty_entry_2 != 0 || $NPC.difficulty_entry_3 != 0}
                (<span class="color-tooltip-green">{($CreatureMaxHP/1000)|string_format:"%.1f"}K</span> {#Zones_Heroic#})
            {/if}
        </li>
        <li>
            <span class="color-tooltip-yellow">{#Type#}:</span>
            {$NPC.type.translation}
        </li>
        {*<li>*}
            {*<span class="color-tooltip-yellow">{#Zones_Place#}:</span>*}
            {*Гундрак, Зул&#39;Драк*}
        {*</li>*}



    </ul>
</div>