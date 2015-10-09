<div class="related-content" id="related-rewardFromQuests">
    <div class="filters inline">
        <div class="keyword">
            <span class="view"></span>
            <span class="reset" style="display: none"></span>
            <input id="filter-name-rewardFromQuests" type="text" class="input filter-name" data-filter="row" maxlength="25" title="{#Filter#}" value="{#Filter#}" />
        </div>
        <span class="clear"><!-- --></span>
    </div>
    <div class="data-options-top">
        <div class="table-options data-options ">
            <span class="clear"><!-- --></span>
        </div>
    </div>
    <div class="data-container table full-width">
        <table style="font-size: 13px;">
            <thead>
            <tr>
                <th>
                    <a href="javascript:;" class="sort-link default">
                        <span class="arrow">{#Profile_Character_Reputation_Title#}</span>
                    </a>

                </th>
                <th class="align-center">
                    <a href="javascript:;" class="sort-link numeric">
                        <span class="arrow">{#Profile_Character_Profession_Table_Level#}</span>
                    </a>

                </th>
                <th class="align-center">
                    <a href="javascript:;" class="sort-link numeric">
                        <span class="arrow">{#Item_Required#}</span>
                    </a>

                </th>
                <th>
                    <a href="javascript:;" class="sort-link numeric">
                        <span class="arrow">{#Item_Rewards#}</span>
                    </a>

                </th>
            </tr>
            </thead>
            <tbody>
            {foreach $Relations as $Relation}
                <tr class="row" >
                    <td>
                        <a href="javascript:;" data-fansite="quest|{$Relation.ID}|{$Relation.LogTitle}" class="fansite-link float-right"> </a>
                        <strong class="has-tip" data-quest="{$Relation.ID}">{$Relation.LogTitle}</strong>
                    </td>
                    <td data-raw="{$Relation.QuestLevel}" class="align-center">
                        {$Relation.QuestLevel}
                    </td>
                    <td data-raw="{$Relation.MinLevel}" class="align-center">
                        {$Relation.MinLevel}
                    </td>
                    <td data-raw="1">
                        {if $Relation.RewardItem1 != 0}
                            <a href="/item/{$Relation.RewardItem1}" class="item-link reagent">
                            <span  class="icon-frame frame-18 " style='background-image: url("/Templates/{$Template}/images/icons/small/{$Relation.RewardItemIcon1}.jpg");'>
                                    {$Relation.RewardItemCount1}
                            </span>
                            </a>
                        {/if}
                        {if $Relation.RewardItem2 != 0}
                            <a href="/item/{$Relation.RewardItem2}" class="item-link reagent">
                            <span  class="icon-frame frame-18 " style='background-image: url("/Templates/{$Template}/images/icons/small/{$Relation.RewardItemIcon2}.jpg");'>
                                    {$Relation.RewardItemCount2}
                            </span>
                            </a>
                        {/if}
                        {if $Relation.RewardItem3 != 0}
                            <a href="/item/{$Relation.RewardItem3}" class="item-link reagent">
                            <span  class="icon-frame frame-18 " style='background-image: url("/Templates/{$Template}/images/icons/small/{$Relation.RewardItemIcon3}.jpg");'>
                                    {$Relation.RewardItemCount3}
                            </span>
                            </a>
                        {/if}
                        {if $Relation.RewardItem4 != 0}
                            <a href="/item/{$Relation.RewardItem4}" class="item-link reagent">
                            <span  class="icon-frame frame-18 " style='background-image: url("/Templates/{$Template}/images/icons/small/{$Relation.RewardItemIcon4}.jpg");'>
                                    {$Relation.RewardItemCount4}
                            </span>
                            </a>
                        {/if}
                    </td>
                </tr>
            {/foreach}
            <tr class="no-results">
                <td colspan="5" class="align-center">
                    {#Results_Nothing_Found#}.
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div  class="data-options-bottom">
        <div class="table-options data-options ">
            <span class="clear"><!-- --></span>
        </div>
    </div>
    <script type="text/javascript">
        //<![CDATA[
        Wiki.related['rewardFromQuests'] = new WikiRelated('rewardFromQuests', {
            paging: true,
            totalResults: {$Relation|count},
            results: 50,
            column: 0,
            method: "default"
        });
        //]]>
    </script>
</div>

