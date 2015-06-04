<div class="data-options ">
    <div class="option">
        <ul class="ui-pagination">
            <li class="current">
                <a href="?f=wowitem&amp;q=Shadow&amp;page=1" data-pagenum="1">
                    <span>1</span>
                </a>
            </li>
        </ul>
    </div>
    {#Results#} <strong class="results-start">1</strong>–<strong class="results-end">50</strong> {#Of#} <strong class="results-total">{$SearchResults.items|count}</strong>
    <span class="clear"><!-- --></span>
</div>
<div class="view-table">
    <div class="table ">
        <table style="font-size:12px;">
            <thead>
            <tr>
                <th class=" first-child">
                    <span class="sort-tab">
                        {#Profile_Character_Profession_Table_Title#}
                    </span>
                </th>
                <th>
                    <span class="sort-tab">{#Profile_Character_Profession_Table_Level#}</span>
                </th>
                <th>
                    <span class="sort-tab">{#Item_Required#}</span>
                </th>
                <th>
                    <span class="sort-tab">{#Profile_Character_Profession_Table_Source#}</span>
                </th>
                <th class=" last-child">
                    <span class="sort-tab">
                        {#Type#}
                    </span>
                </th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$SearchResults.items item=Item key=i}
                <tr class="row{if $i%2==1}2{else}1{/if}">
                    <td>
                        <a href="/item/{$Item.id}" class="item-link color-q{$Item.Quality}" data-item="{$Item.id}">
                            <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Item.icon}.jpg&quot;);">
                            </span>
                            <strong>{$Item.name}</strong>
                        </a>
                    </td>
                    <td class="align-center">
                        {$Item.ItemLevel}
                    </td>
                    <td class="align-center">
                        {$Item.RequiredLevel}
                    </td>
                    <td>

                    </td>
                    <td>
                        {$Item.Class}
                        <em>({$Item.Subclass})</em>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>