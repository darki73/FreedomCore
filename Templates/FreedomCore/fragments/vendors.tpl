<div class="related-content" id="related-vendors">
    <div class="filters inline">
        <div class="keyword">
            <span class="view"></span>
            <span class="reset" style="display: none"></span>
            <input id="filter-name-vendors" type="text" class="input filter-name" data-filter="row" maxlength="25" title="{#Filter#}" value="{#Filter#}" />
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
                <th>
                    <a href="javascript:;" class="sort-link">
                        <span class="arrow">{#Item_Seller_Zone#}</span>
                    </a>

                </th>
            </tr>
            </thead>
            <tbody>
            {if !empty($Relations)}
                {foreach $Relations as $Relation}
                    <tr class="row1">
                        <td>
                            <a href="javascript:;" data-fansite="npc|{$Relation.entry}|{$Relation.name}" class="fansite-link float-right"> </a>
                            <strong>{$Relation.name}</strong>
                            <em>&#60;{$Relation.subname}&#62;</em>
                        </td>
                        <td data-raw="{$Relation.maxlevel}" class="align-center">
                            {$Relation.maxlevel}
                        </td>
                        <td>
                            {$Relation.map}
                        </td>
                    </tr>
                {/foreach}
            {else}
                <tr class="no-results">
                    <td colspan="5" class="align-center">
                        {#Results_Nothing_Found#}.
                    </td>
                </tr>
            {/if}
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