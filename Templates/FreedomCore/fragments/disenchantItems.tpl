<div class="related-content" id="related-disenchantItems">
    <div class="filters inline">
        <div class="keyword">
            <span class="view"></span>
            <span class="reset" style="display: none"></span>
            <input id="filter-name-disenchantItems" type="text" class="input filter-name" data-filter="row" maxlength="25" title="{#Filter#}" value="{#Filter#}" />
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
                    <a href="javascript:;" class="sort-link">
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
                        <span class="arrow">{#Item_Disenchant_Amount#}</span>
                    </a>

                </th>
                <th>
                    <a href="javascript:;" class="sort-link numeric">
                        <span class="arrow">{#Item_Disenchant_Probability#}</span>
                    </a>

                </th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$Relations item=Relation key=i}
                <tr class="row{if $i%2==1}2{else}1{/if}">
                    <td data-raw="{$Relation.Disenchants_Into.name}">
                        <a href="/item/{$Relation.Disenchants_Into.entry}" data-item="{$Relation.Disenchants_Into.entry}" class="item-link color-q{$Relation.Disenchants_Into.Quality}">
                        <span  class="icon-frame frame-18 " style='background-image: url("/Templates/{$Template}/images/icons/small/{$Relation.Disenchants_Into.icon}.jpg");'>
                        </span>
                            <strong>{$Relation.Disenchants_Into.name}</strong>
                        </a>
                    </td>
                    <td class="align-center" data-raw="{$Relation.Disenchants_Into.ItemLevel}">
                        {$Relation.Disenchants_Into.ItemLevel}
                    </td>
                    <td class="align-center" data-raw="{$Relation.MaxCount}">
                        {$Relation.MaxCount}
                    </td>
                    <td data-raw="{$Relation.Chance}">
                        {$Relation.Chance_Translation}
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
