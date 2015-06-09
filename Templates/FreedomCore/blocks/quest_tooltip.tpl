<div class="wiki-tooltip">
    <h3>
        <span class="color-q0 float-right">{#Profile_Character_Profession_Table_Level#} {$Quest.Level}</span>
        {$Quest.Title}
    </h3>
    <div class="color-tooltip-yellow">
        {$Quest.Objectives}
    </div>
    <ul class="wiki-list">
        <li class="color-tooltip-yellow">{#Item_Rewards#}:</li>
        <li>
            <span class="item-link color-q5">
            <span class="icon-frame frame-14 ">
            <img src="http://media.blizzard.com/wow/icons/18/inv_axe_113.jpg" alt="" width="14" height="14"/>
            </span>
            Темная Скорбь
            </span>
        </li>
    </ul>
    <ul class="wiki-list">
        <li class="color-tooltip-yellow">{#Item_Quest_Payment#}:</li>
        <li class="indent-small">
            {if isset($Quest.MoneyReward.gold)}
                <span class="icon-gold">{$Quest.MoneyReward.gold}</span>
            {/if}
            {if isset($Quest.MoneyReward.silver)}
                <span class="icon-silver">{$Quest.MoneyReward.silver}</span>
            {/if}
            {if isset($Quest.MoneyReward.copper)}
                <span class="icon-copper">{$Quest.MoneyReward.copper}</span>
            {/if}
        </li>
        <li class="indent-small">{#Item_Quest_Exp_Reward#}: HAHA 800 VALUES in CODE!! FUCK NO! ($Quest.</li>
        {if $Quest.RewardFactionValueIdOverride1 != 0}
            <li class="indent-small">+{#Item_Quest_Add_Rep#} «{$Quest.factionname}»: {$Quest.RewardFactionValueIdOverride1/100}</li>
        {/if}
    </ul>
</div>