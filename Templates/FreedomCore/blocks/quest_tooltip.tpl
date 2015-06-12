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
        {for $i = 1; $i <= 4; $i++}
            {assign 'RewardItemID' 'RewardItemId'|cat:$i}
            {if $Quest.$RewardItemID != 0}
                <li>
                    <span class="item-link color-q{$Quest.$RewardItemID.Quality}">
                    <span class="icon-frame frame-14 ">
                    <img src="/Templates/{$Template}/images/icons/small/{$Quest.$RewardItemID.icon}.jpg" alt="" width="14" height="14"/>
                    </span>
                    {$Quest.$RewardItemID.name}
                    </span>
                </li>
            {/if}
        {/for}
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
        <li class="indent-small">{#Item_Quest_Exp_Reward#}: +{$Quest.XPReward}</li>
        {if $Quest.RewardFactionValueIdOverride1 != 0}
            <li class="indent-small">+{#Item_Quest_Add_Rep#} «{$Quest.factionname}»: {$Quest.RewardFactionValueIdOverride1/100}</li>
        {/if}
    </ul>
</div>