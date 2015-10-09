<div class="related-content" id="related-achievements">
    <div class="filters inline">
        <div class="keyword">
            <span class="view"></span>
            <span class="reset" style="display: none"></span>
            <input id="filter-name-achievements" type="text" class="input filter-name" data-filter="row" maxlength="25" title="{#Filter#}" value="{#Filter#}" />
        </div>
        <span class="clear"><!-- --></span>
    </div>
    <div class="data-options-top">
        <div class="table-options data-options ">
            {#Results#} <strong class="results-start">1</strong>–<strong class="results-end">{$AchievementsCount}</strong> {#Of#} <strong class="results-total">{$AchievementsCount}</strong>
            <span class="clear"><!-- --></span>
        </div>
    </div>


    <div class="data-container table full-width">
        <table style="font-size: 13px;">
            <thead>
            <tr>
                <th>
                    <a href="javascript:;" class="sort-link default">
                        <span class="arrow">Название</span>
                    </a>
                </th>
                <th>
                    <a href="javascript:;" class="sort-link default">
                        <span class="arrow">Описание</span>
                    </a>
                </th>
                <th class="align-center">
                    <a href="javascript:;" class="sort-link numeric">
                        <span class="arrow">Очки</span>
                    </a>
                </th>
                <th>
                    <a href="javascript:;" class="sort-link default">
                        <span class="arrow">Категория</span>
                    </a>
                </th>
            </tr>
            </thead>
            <tbody>
            {foreach $Achievements as $Achievement}
                <tr class="row">
                    <td>
                        <a href="javascript:;" data-fansite="achievement|{$Achievement.Achievement}|{$Achievement.Name}" class="fansite-link float-right"> </a>
                        <strong class="item-link" data-achievement="{$Achievement.Achievement}">
                            <span  class="icon-frame frame-18 " style='background-image: url("/Templates/{$Template}/images/icons/small/{$Achievement.Icon}.jpg");'></span>
                            <span class="has-tip">{$Achievement.Name}</span>
                        </strong>
                    </td>
                    <td>
                        {$Achievement.Description|truncate:45:"..."}
                    </td>
                    <td class="align-center">
                        {if $Achievement.Points != 0}
                            {$Achievement.Points}<span class="icon-achievement-points"></span>
                        {/if}
                    </td>
                    <td>
                        {$Achievement.Category|truncate:20:"..."}
                    </td>
                </tr>
            {/foreach}
            <tr class="no-results">
                <td colspan="4" class="align-center">
                    {#Results_Nothing_Found#}
                </td>
            </tr>

            </tbody>
        </table>
    </div>
    <div class="data-options-bottom">
        <div class="table-options data-options ">
            {#Results#} <strong class="results-start">1</strong>–<strong class="results-end">{$AchievementsCount}</strong> {#Of#} <strong class="results-total">{$AchievementsCount}</strong>
            <span class="clear"><!-- --></span>
        </div>
    </div>
    <script type="text/javascript">
        //<![CDATA[
        Wiki.related['achievements'] = new WikiRelated('achievements', {
            paging: true,
            totalResults: {$AchievementsCount},
            column: 0,
            method: 'default',
            type: 'asc'
        });
        //]]>
    </script>
</div>