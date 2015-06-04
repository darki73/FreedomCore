<div class="profile-filters" id="profession-filters">
    <div class="keyword">
        <span class="view"></span>
        <span class="reset" style="display: none"></span>
        <input class="input" id="filter-keyword" type="text" value="Фильтр" alt="Фильтр" data-filter="row" data-name="filter" />
    </div>
    <div class="tabs">
        {$UnlearnedRecipes = $TotalRecipes - count($LearnedRecipes)}
        <a href="javascript:;" data-status="learned" class="tab-active">
            {#Profile_Character_Profession_Collected#} ({$LearnedRecipes|count})
        </a>
        <a href="javascript:;" data-status="unknown">
            {#Profile_Character_Profession_NotCollected#} ({$UnlearnedRecipes})
        </a>
    </div>
</div>
<div id="profession-loading" style="display: none;"></div>
<div id="professions" style="display:block;">
    <div class="table-options data-options table-top">
        <div class="option">
            <ul class="ui-pagination"></ul>
        </div>
        {#Results#}<strong class="results-start">0</strong>–<strong class="results-end">0</strong> {#Of#} <strong class="results-total">0</strong>
        <span class="clear"><!-- --></span>
    </div>
    <div class="data-container type-table">
        <div class="table ">
            <table>
                <thead>
                <tr>
                    <th class=" first-child">
                        <a href="javascript:;" class="sort-link default">
				        <span class="arrow">
                            {#Profile_Character_Profession_Table_Title#}
                        </span>
                        </a>
                    </th>
                    <th class="align-center">
                        <a href="javascript:;" class="sort-link numeric">
				        <span class="arrow">
                            {#Profile_Character_Profession_Table_Level#}
                        </span>
                        </a>
                    </th>
                    <th class="align-center">
                        <a href="javascript:;" class="sort-link numeric">
				        <span class="arrow">
                            {if $SelectedProfession == 'archeology'}
                                {#Profile_Character_Profession_Table_Fragment#}
                            {else}
                                {#Profile_Character_Profession_Table_Regs#}
                            {/if}
                        </span>
                        </a>
                    </th>
                    <th class="align-center">
                        <a href="javascript:;" class="sort-link numeric">
				        <span class="arrow">
                            {if $SelectedProfession == 'archeology'}
                                {#Profile_Character_Profession_Table_KeyFragment#}
                            {else}
                                {#Profile_Character_Profession_Table_Source#}
                            {/if}
                        </span>
                        </a>

                    </th>
                    <th class=" last-child">
                        <a href="javascript:;" class="sort-link default">
				        <span class="arrow down">
                            {if $SelectedProfession == 'archeology'}
                                {#Profile_Character_Profession_Table_Project#}
                            {else}
                                {#Profile_Character_Profession_Table_Skill#}
                            {/if}
                        </span>
                        </a>
                    </th>
                </tr>
                </thead>
                <tbody>
                {if empty($LearnedRecipes)}
                    <tr class="no-results" style="display: table-row;">
                        <td colspan="5">{#Results_Nothing_Found#}.</td>
                    </tr>
                {else}
                    {foreach $LearnedRecipes as $Recipe}
                        {if $Recipe.effect1itemtype != 0}
                            <tr class="learned" style="font-size: 12px;">
                                <td data-raw="{$Recipe.recipe_name}">
                                    <a href="/item/{$Recipe.effect1itemtype}" class="item-link color-q{$Recipe.effect1itemquality}" data-item="{$Recipe.effect1itemtype}">
                                        <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Recipe.resultingitem_icon}.jpg&quot;);"></span>
                                        <strong>{$Recipe.recipe_name}</strong>
                                    </a>
								<span style="display: none">
		                            Расходуемые
								</span>
                                </td>
                                <td class="align-center" data-raw="">

                                </td>
                                <td class="reagents" data-raw="2">
                                    <div class="reagent-list">
                                        {if $Recipe.reagent1 != 0}
                                            <a href="/item/{$Recipe.reagent1}" class="item-link reagent" data-item="{$Recipe.reagent1}">
                                            <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Recipe.reagent1_icon}.jpg&quot;);">
                                                {$Recipe.reagentcount1}
                                            </span>
                                                <span style="display: none">{$Recipe.reagent1_name}</span>
                                            </a>
                                        {/if}
                                        {if $Recipe.reagent2 != 0}
                                            <a href="/item/{$Recipe.reagent2}" class="item-link reagent" data-item="{$Recipe.reagent2}">
                                            <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Recipe.reagent2_icon}.jpg&quot;);">
                                                {$Recipe.reagentcount2}
                                            </span>
                                                <span style="display: none">{$Recipe.reagent2_name}</span>
                                            </a>
                                        {/if}
                                        {if $Recipe.reagent3 != 0}
                                            <a href="/item/{$Recipe.reagent3}" class="item-link reagent" data-item="{$Recipe.reagent3}">
                                            <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Recipe.reagent3_icon}.jpg&quot;);">
                                                {$Recipe.reagentcount3}
                                            </span>
                                                <span style="display: none">{$Recipe.reagent3_name}</span>
                                            </a>
                                        {/if}
                                        {if $Recipe.reagent4 != 0}
                                            <a href="/item/{$Recipe.reagent4}" class="item-link reagent" data-item="{$Recipe.reagent4}">
                                            <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Recipe.reagent4_icon}.jpg&quot;);">
                                                {$Recipe.reagentcount4}
                                            </span>
                                                <span style="display: none">{$Recipe.reagent4_name}</span>
                                            </a>
                                        {/if}
                                        {if $Recipe.reagent5 != 0}
                                            <a href="/item/{$Recipe.reagent5}" class="item-link reagent" data-item="{$Recipe.reagent5}">
                                            <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Recipe.reagent5_icon}.jpg&quot;);">
                                                {$Recipe.reagentcount5}
                                            </span>
                                                <span style="display: none">{$Recipe.reagent5_name}</span>
                                            </a>
                                        {/if}
                                        <span class="clear"><!-- --></span>
                                    </div>
                                </td>
                                <td class="source" data-raw="-3">
                                </td>
                                <td data-raw="{$Recipe.req_skill_value}" class="align-center">
                                    <span class="tip recipe-skill" data-skillupchance="{$Recipe.req_skill_value}">{$Recipe.req_skill_value}</span>
                                </td>
                            </tr>
                        {/if}
                    {/foreach}
                {/if}
                {if empty($UnlearnedRecipes)}
                    <tr class="no-results" style="display: table-row;">
                        <td colspan="5">{#Results_Nothing_Found#}.</td>
                    </tr>
                {else}
                    {foreach $UnRecipes as $URecipe}
                        {if $URecipe.effect1itemtype != 0}
                            <tr class="unknown" style="font-size: 12px;">
                                <td data-raw="{$URecipe.recipe_name}">
                                    <a href="/item/{$URecipe.effect1itemtype}" class="item-link color-q2" data-item="{$URecipe.effect1itemtype}">
                                        <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$URecipe.resultingitem_icon}.jpg&quot;);"></span>
                                        <strong>{$URecipe.recipe_name}</strong>
                                    </a>
								<span style="display: none">
		                            Расходуемые
								</span>
                                </td>
                                <td class="align-center" data-raw="">

                                </td>
                                <td class="reagents" data-raw="2">
                                    <div class="reagent-list">
                                        {if $URecipe.reagent1 != 0}
                                            <a href="/item/{$URecipe.reagent1}" class="item-link reagent" data-item="{$URecipe.reagent1}">
                                            <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$URecipe.reagent1_icon}.jpg&quot;);">
                                                {$URecipe.reagentcount1}
                                            </span>
                                                <span style="display: none">{$URecipe.reagent1_name}</span>
                                            </a>
                                        {/if}
                                        {if $URecipe.reagent2 != 0}
                                            <a href="/item/{$URecipe.reagent2}" class="item-link reagent" data-item="{$URecipe.reagent2}">
                                            <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$URecipe.reagent2_icon}.jpg&quot;);">
                                                {$URecipe.reagentcount2}
                                            </span>
                                                <span style="display: none">{$URecipe.reagent2_name}</span>
                                            </a>
                                        {/if}
                                        {if $URecipe.reagent3 != 0}
                                            <a href="/item/{$URecipe.reagent3}" class="item-link reagent" data-item="{$URecipe.reagent3}">
                                            <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$URecipe.reagent3_icon}.jpg&quot;);">
                                                {$URecipe.reagentcount3}
                                            </span>
                                                <span style="display: none">{$URecipe.reagent3_name}</span>
                                            </a>
                                        {/if}
                                        {if $URecipe.reagent4 != 0}
                                            <a href="/item/{$URecipe.reagent4}" class="item-link reagent" data-item="{$URecipe.reagent4}">
                                            <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$URecipe.reagent4_icon}.jpg&quot;);">
                                                {$URecipe.reagentcount4}
                                            </span>
                                                <span style="display: none">{$URecipe.reagent4_name}</span>
                                            </a>
                                        {/if}
                                        {if $URecipe.reagent5 != 0}
                                            <a href="/item/{$URecipe.reagent5}" class="item-link reagent" data-item="{$URecipe.reagent5}">
                                            <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$URecipe.reagent5_icon}.jpg&quot;);">
                                                {$URecipe.reagentcount5}
                                            </span>
                                                <span style="display: none">{$URecipe.reagent5_name}</span>
                                            </a>
                                        {/if}
                                        <span class="clear"><!-- --></span>
                                    </div>
                                </td>
                                <td class="source" data-raw="-3">
                                </td>
                                <td data-raw="{$URecipe.req_skill_value}" class="align-center">
                                    <span class="tip recipe-skill" data-skillupchance="{$URecipe.req_skill_value}">{$URecipe.req_skill_value}</span>
                                </td>
                            </tr>
                        {/if}
                    {/foreach}
                {/if}
                </tbody>
            </table>
        </div>
    </div>
    <div class="table-options data-options table-bottom">
        <div class="option">
            <ul class="ui-pagination"></ul>
        </div>
        {#Results#}<strong class="results-start">0</strong>–<strong class="results-end">0</strong> {#Of#} <strong class="results-total">0</strong>
        <span class="clear"><!-- --></span>
    </div>
</div>
<script type="text/javascript">
    //<![CDATA[
    $(document).ready(function() {
        Profession.typeId = {$ProfessionInfo.id};
        Profession.table = DataSet.factory('#professions', {
            paging: true,
            totalResults: 208,
            elementControls: '.data-options'
        });

        RecipeTable.init('#professions', {
            characterMode: true,
            name: '{$ProfessionInfo.translation}'
        });

    });
    //]]>
</script>