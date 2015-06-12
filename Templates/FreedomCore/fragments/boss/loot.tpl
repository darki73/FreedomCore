
<div class="related-content" id="related-loot">
    <div class="filters inline">
        <div class="keyword">
            <span class="view"></span>
            <span class="reset" style="display: none"></span>
            <input id="filter-name-loot" type="text" class="input filter-name" data-filter="row" maxlength="25" title="{#Filter#}" value="{#Filter#}" />
        </div>
        <span class="clear"><!-- --></span>
    </div>
    <div class="data-options-top">
        <div class="table-options data-options ">
            {#Results#} <strong class="results-start">1</strong>–<strong class="results-end">{$LootAmount}</strong> {#Of#} <strong class="results-total">{$LootAmount}</strong>
            <span class="clear"><!-- --></span>
        </div>
    </div>
    <div class="data-container table full-width">
        <table>
            <thead>
            <tr>
                <th>
                    <a href="javascript:;" class="sort-link default">
                        <span class="arrow">Название</span>
                    </a>
                </th>
                <th class="align-center">
                    <a href="javascript:;" class="sort-link numeric">
                        <span class="arrow">Уровень</span>
                    </a>
                </th>
                <th>
                    <a href="javascript:;" class="sort-link default">
                        <span class="arrow">Тип</span>
                    </a>
                </th>
                <th>
                    <a href="javascript:;" class="sort-link default">
                        <span class="arrow">Режим</span>
                    </a>

                </th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$BossLoot item=LootGroup key=difficulty}
                {foreach $LootGroup as $Item}
                    <tr class="row">
                        <td data-raw="{$Item.name}">
                            <a href="/item/{$Item.entry}" class="item-link color-q{$Item.Quality}" data-item="{$Item.entry}">
                                <span  class="icon-frame frame-18 " style='background-image: url("/Templates/{$Template}/images/icons/small/{$Item.icon}.jpg");'></span>
                                <strong>{$Item.name}</strong>
                            </a>
                        </td>
                        <td class="align-center" data-raw="{$Item.RequiredLevel}">
                            {$Item.RequiredLevel}
                        </td>
                        <td>
                            {$Item.class.translation}
                        </td>
                        {if $difficulty == 'dungheroicortenman' || $difficulty == 'twentyfiveheroic'}
                            <td data-raw="Героический ">
                                Героический
                            </td>
                        {else}
                            <td data-raw="Нормальный ">
                                Нормальный
                            </td>
                        {/if}
                    </tr>
                {/foreach}
            {/foreach}
            <tr class="no-results">
                <td colspan="5" class="align-center">
                    {#Results_Nothing_Found#}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="data-options-bottom">
        <div class="table-options data-options ">
            {#Results#} <strong class="results-start">1</strong>–<strong class="results-end">{$LootAmount}</strong> {#Of#} <strong class="results-total">{$LootAmount}</strong>
            <span class="clear"><!-- --></span>
        </div>
    </div>

    <script type="text/javascript">
        //<![CDATA[
        Wiki.related['loot'] = new WikiRelated('loot', {
            paging: true,
            totalResults: {$LootAmount},
            column: 0,
            method: 'default',
            type: 'asc'
        });

        //]]>
    </script>
</div>
