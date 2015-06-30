<div class="wiki-tooltip">
    <h3>
        <span class="color-q0 float-right">{#Profile_Character_Profession_Table_Level#} {$Quest.QuestLevel}</span>
        {$Quest.LogTitle}
    </h3>
    <div class="color-tooltip-yellow">
        {$Quest.LogDescription}
    </div>
    <ul class="wiki-list">
        <li class="color-tooltip-yellow">{#Item_Rewards#}:</li>
        {for $i = 1; $i <= 4; $i++}
            {assign 'RewardItem' 'RewardItem'|cat:$i}
            {if $Quest.$RewardItem != 0}
                <li>
                    <span class="item-link color-q{$Quest.$RewardItem.Quality}">
                    <span class="icon-frame frame-14 ">
                    <img src="/Templates/{$Template}/images/icons/small/{$Quest.$RewardItem.icon}.jpg" alt="" width="14" height="14"/>
                    </span>
                    {$Quest.$RewardItem.name}
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
        {if $Quest.RewardFactionOverride1 != 0}
            <li class="indent-small">+{#Item_Quest_Add_Rep#} «{$Quest.factionname}»: {$Quest.RewardFactionOverride1/100}</li>
        {/if}
    </ul>
</div>