{include file="header.tpl"}
<div id="content">
    <div class="content-top body-top">
        <div class="content-trail">
            <ol class="ui-breadcrumb">
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$AppName}</span>
                    </a>
                </li>
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/game/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Menu_Game#}</span>
                    </a>
                </li>

                {if $Requests.subclass == 1 && $Requests.class == 1}
                    <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                        <a href="/item" rel="np" class="breadcrumb-arrow" itemprop="url">
                            <span class="breadcrumb-text" itemprop="name">{#Item_Category#}</span>
                        </a>
                    </li>
                    <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                        <a href="/item/?classId={$PageData.name.class}" rel="np" class="breadcrumb-arrow" itemprop="url">
                            <span class="breadcrumb-text" itemprop="name">{$PageData.name.translation}</span>
                        </a>
                    </li>
                    <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                        <a href="/item/?classId={$PageData.name.class}&subClassId={$PageData.subname.subclass}" rel="np" itemprop="url">
                            <span class="breadcrumb-text" itemprop="name">{$PageData.subname.translation}</span>
                        </a>
                    </li>
                {elseif $Requests.class == 1 && $Requests.subclass == 0}
                    <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                        <a href="/item" rel="np" class="breadcrumb-arrow" itemprop="url">
                            <span class="breadcrumb-text" itemprop="name">{#Item_Category#}</span>
                        </a>
                    </li>
                    <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                        <a href="/item/?classId={$PageData.name.class}" rel="np" itemprop="url">
                            <span class="breadcrumb-text" itemprop="name">{$PageData.name.translation}</span>
                        </a>
                    </li>
                {elseif $Requests.class == 0 && $Requests.subclass == 0}
                    <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                        <a href="/item/" rel="np" itemprop="url">
                            <span class="breadcrumb-text" itemprop="name">{#Item_Category#}</span>
                        </a>
                    </li>
                {/if}
            </ol>
        </div>
        <div class="content-bot clear">
            <div id="wiki" class="wiki directory wiki-item">
                <div class="title">
                    <h2>
                        {#Item_Category#}
                    </h2>
                </div>
                <div class="item-results" id="item-results">
                    <div class="table-options data-options">
                        <div class="option">
                            <ul class="ui-pagination">
                            {$CountPages = ($ResultsFound/50)}
                                {if $CountPages > $CountPages|string_format:"%d"}
                                    {$OriginalPages = $CountPages}
                                    {$CountPages = $CountPages|string_format:"%d" + 1}
                                {/if}
                                {for $i = 1; $i <= $CountPages; $i++}
                                    {if $CountPages < 10}
                                        {if $i == $SelectedPage}
                                            <li class="current">
                                                {else}
                                            <li>
                                        {/if}
                                        <a href="/item/{if $Requests.subclass == 1 && $Requests.class == 1}?classId={$PageData.name.class}&amp;subClassId={$PageData.subname.subclass}&amp;page={$i}{elseif $Requests.class == 1 && $Requests.subclass == 0}?classId={$PageData.name.class}&amp;page={$i}{elseif $Requests.class == 0 && $Requests.subclass == 0}?page={$i}{/if}" data-pagenum="{$i}">
                                            <span>{$i}</span>
                                        </a>
                                        </li>
                                    {else}
                                        {if $SelectedPage < 6}
                                            {if $i < 8}
                                                {if $i == $SelectedPage}
                                                    <li class="current">
                                                        {else}
                                                    <li>
                                                {/if}
                                                <a href="/item/{if $Requests.subclass == 1 && $Requests.class == 1}?classId={$PageData.name.class}&amp;subClassId={$PageData.subname.subclass}&amp;page={$i}{elseif $Requests.class == 1 && $Requests.subclass == 0}?classId={$PageData.name.class}&amp;page={$i}{elseif $Requests.class == 0 && $Requests.subclass == 0}?page={$i}{/if}" data-pagenum="{$i}">
                                                    <span>{$i}</span>
                                                </a>
                                                </li>
                                            {elseif $i == 8}
                                                <li class="expander"><span>…</span></li>
                                                <li>
                                                    <a href="/item/{if $Requests.subclass == 1 && $Requests.class == 1}?classId={$PageData.name.class}&amp;subClassId={$PageData.subname.subclass}&amp;page={$CountPages}{elseif $Requests.class == 1 && $Requests.subclass == 0}?classId={$PageData.name.class}&amp;page={$CountPages}{elseif $Requests.class == 0 && $Requests.subclass == 0}?page={$CountPages}{/if}" data-pagenum="{$CountPages}">
                                                        <span>{$CountPages}</span>
                                                    </a>
                                                </li>
                                            {/if}
                                        {elseif $SelectedPage >= 6}
                                            {if $i == 1}
                                                <li>
                                                    <a href="/item/{if $Requests.subclass == 1 && $Requests.class == 1}?classId={$PageData.name.class}&amp;subClassId={$PageData.subname.subclass}&amp;page=1{elseif $Requests.class == 1 && $Requests.subclass == 0}?classId={$PageData.name.class}&amp;page=1{elseif $Requests.class == 0 && $Requests.subclass == 0}?page=1{/if}" data-pagenum="1">
                                                        <span>1</span>
                                                    </a>
                                                </li>
                                                <li class="expander"><span>…</span></li>
                                            {elseif $i >= ($SelectedPage - 3) && $i <= ($SelectedPage + 3)}
                                                {if $i == $SelectedPage}
                                                    <li class="current">
                                                        {else}
                                                    <li>
                                                {/if}
                                                <a href="/item/{if $Requests.subclass == 1 && $Requests.class == 1}?classId={$PageData.name.class}&amp;subClassId={$PageData.subname.subclass}&amp;page={$i}{elseif $Requests.class == 1 && $Requests.subclass == 0}?classId={$PageData.name.class}&amp;page={$i}{elseif $Requests.class == 0 && $Requests.subclass == 0}?page={$i}{/if}" data-pagenum="{$i}">
                                                    <span>{$i}</span>
                                                </a>
                                                </li>
                                            {else}
                                                {if $i == $CountPages}
                                                    <li class="expander"><span>…</span></li>
                                                    <li>
                                                        <a href="/item/{if $Requests.subclass == 1 && $Requests.class == 1}?classId={$PageData.name.class}&amp;subClassId={$PageData.subname.subclass}&amp;page={$CountPages}{elseif $Requests.class == 1 && $Requests.subclass == 0}?classId={$PageData.name.class}&amp;page={$CountPages}{elseif $Requests.class == 0 && $Requests.subclass == 0}?page={$CountPages}{/if}" data-pagenum="{$CountPages}">
                                                            <span>{$CountPages}</span>
                                                        </a>
                                                    </li>
                                                {/if}
                                            {/if}
                                        {/if}
                                    {/if}
                                {/for}

                            </ul>
                        </div>
                        {if $SelectedPage == 1}
                            {#Results#} <strong class="results-start">1</strong>–<strong class="results-end">50</strong> {#Of#} <strong class="results-total">{$ResultsFound}</strong>
                        {elseif $SelectedPage < $OriginalPages}
                            {#Results#} <strong class="results-start">{$SelectedPage*50-50}</strong>–<strong class="results-end">{$SelectedPage*50}</strong> {#Of#} <strong class="results-total">{$ResultsFound}</strong>
                        {else}
                            {#Results#} <strong class="results-start">{$ResultsFound - ($ResultsFound - (($SelectedPage-1)*50))}</strong>–<strong class="results-end">{$ResultsFound}</strong> {#Of#} <strong class="results-total">{$ResultsFound}</strong>
                        {/if}
                        <span class="clear"><!-- --></span>
                    </div>
                    <div class="table full-width">
                        <table style="font-size:13px;">
                            <thead>
                            <tr>
                                <th>
                                    <span class="sort-tab">{#Profile_Character_Profession_Table_Title#}</span>
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
                                <th>
                                    <span class="sort-tab">{#Type#}</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                {foreach from=$Items item=Item key=i}
                                    <tr class="row{if $i%2==1}2{else}1{/if}">
                                        <td data-raw="{$Item.name}">
                                            <a href="/item/{$Item.entry}" class="item-link color-q{$Item.Quality}" data-item="{$Item.entry}">
                                                <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Item.icon}.jpg&quot;);">
                                                </span>
                                                <strong>{$Item.name}</strong>

                                            </a>
                                        </td>
                                        <td class="align-center">
                                            {$Item.ItemLevel}
                                        </td>
                                        <td class="align-center" data-raw="{$Item.RequiredLevel}">
                                            {$Item.RequiredLevel}
                                        </td>
                                        <td></td>
                                        <td>
                                            {$Item.class.translation}

                                            <em>({$Item.subclass.translation})</em>
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                    <div class="table-options data-options">
                        <div class="option">
                            <ul class="ui-pagination">
                                {$CountPages = ($ResultsFound/50)}
                                {if $CountPages > $CountPages|string_format:"%d"}
                                    {$CountPages = $CountPages + 1}
                                {/if}
                                {for $i = 1; $i <= $CountPages; $i++}
                                    {if $CountPages < 10}
                                        {if $i == $SelectedPage}
                                            <li class="current">
                                                {else}
                                            <li>
                                        {/if}
                                        <a href="/item/{if $Requests.subclass == 1 && $Requests.class == 1}?classId={$PageData.name.class}&amp;subClassId={$PageData.subname.subclass}&amp;page={$i}{elseif $Requests.class == 1 && $Requests.subclass == 0}?classId={$PageData.name.class}&amp;page={$i}{elseif $Requests.class == 0 && $Requests.subclass == 0}?page={$i}{/if}" data-pagenum="{$i}">
                                            <span>{$i}</span>
                                        </a>
                                        </li>
                                    {else}
                                        {if $SelectedPage < 6}
                                            {if $i < 8}
                                                {if $i == $SelectedPage}
                                                    <li class="current">
                                                        {else}
                                                    <li>
                                                {/if}
                                                <a href="/item/{if $Requests.subclass == 1 && $Requests.class == 1}?classId={$PageData.name.class}&amp;subClassId={$PageData.subname.subclass}&amp;page={$i}{elseif $Requests.class == 1 && $Requests.subclass == 0}?classId={$PageData.name.class}&amp;page={$i}{elseif $Requests.class == 0 && $Requests.subclass == 0}?page={$i}{/if}" data-pagenum="{$i}">
                                                    <span>{$i}</span>
                                                </a>
                                                </li>
                                            {elseif $i == 8}
                                                <li class="expander"><span>…</span></li>
                                                <li>
                                                    <a href="/item/{if $Requests.subclass == 1 && $Requests.class == 1}?classId={$PageData.name.class}&amp;subClassId={$PageData.subname.subclass}&amp;page={$CountPages}{elseif $Requests.class == 1 && $Requests.subclass == 0}?classId={$PageData.name.class}&amp;page={$CountPages}{elseif $Requests.class == 0 && $Requests.subclass == 0}?page={$CountPages}{/if}" data-pagenum="{$CountPages}">
                                                        <span>{$CountPages}</span>
                                                    </a>
                                                </li>
                                            {/if}
                                        {elseif $SelectedPage >= 6}
                                            {if $i == 1}
                                                <li>
                                                    <a href="/item/{if $Requests.subclass == 1 && $Requests.class == 1}?classId={$PageData.name.class}&amp;subClassId={$PageData.subname.subclass}&amp;page=1{elseif $Requests.class == 1 && $Requests.subclass == 0}?classId={$PageData.name.class}&amp;page=1{elseif $Requests.class == 0 && $Requests.subclass == 0}?page=1{/if}" data-pagenum="1">
                                                        <span>1</span>
                                                    </a>
                                                </li>
                                                <li class="expander"><span>…</span></li>
                                            {elseif $i >= ($SelectedPage - 3) && $i <= ($SelectedPage + 3)}
                                                {if $i == $SelectedPage}
                                                    <li class="current">
                                                        {else}
                                                    <li>
                                                {/if}
                                                <a href="/item/{if $Requests.subclass == 1 && $Requests.class == 1}?classId={$PageData.name.class}&amp;subClassId={$PageData.subname.subclass}&amp;page={$i}{elseif $Requests.class == 1 && $Requests.subclass == 0}?classId={$PageData.name.class}&amp;page={$i}{elseif $Requests.class == 0 && $Requests.subclass == 0}?page={$i}{/if}" data-pagenum="{$i}">
                                                    <span>{$i}</span>
                                                </a>
                                                </li>
                                            {else}
                                                {if $i == $CountPages}
                                                    <li class="expander"><span>…</span></li>
                                                    <li>
                                                        <a href="/item/{if $Requests.subclass == 1 && $Requests.class == 1}?classId={$PageData.name.class}&amp;subClassId={$PageData.subname.subclass}&amp;page={$CountPages}{elseif $Requests.class == 1 && $Requests.subclass == 0}?classId={$PageData.name.class}&amp;page={$CountPages}{elseif $Requests.class == 0 && $Requests.subclass == 0}?page={$CountPages}{/if}" data-pagenum="{$CountPages}">
                                                            <span>{$CountPages}</span>
                                                        </a>
                                                    </li>
                                                {/if}
                                            {/if}
                                        {/if}
                                    {/if}
                                {/for}

                            </ul>
                        </div>
                        {if $SelectedPage == 1}
                            {#Results#} <strong class="results-start">1</strong>–<strong class="results-end">50</strong> {#Of#} <strong class="results-total">{$ResultsFound}</strong>
                        {elseif $SelectedPage < $CountPages}
                            {#Results#} <strong class="results-start">{$SelectedPage*50-50}</strong>–<strong class="results-end">{$SelectedPage*50}</strong> {#Of#} <strong class="results-total">{$ResultsFound}</strong>
                        {else}

                        {/if}
                        <span class="clear"><!-- --></span>
                    </div>
                </div>
                <span class="clear"><!-- --></span>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}