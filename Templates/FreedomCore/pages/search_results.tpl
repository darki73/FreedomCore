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
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/search/" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Search#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div class="search">
                <div class="search-right">
                    <div class="search-header">
                        <form action="/search" method="get" class="search-form">
                            <div>
                                <input id="search-page-field" type="text" name="q" maxlength="200" tabindex="2" value="{$SearchedFor}" />
                                <button class="ui-button button1" type="submit"><span class="button-left"><span class="button-right">{#Search#}</span></span></button>
                            </div>
                        </form>
                    </div>
                    {if $SearchResults.foundtotal == 0}
                        <div class="no-results">
                            <h2 class="subheader-2">
                                {#Search_Not_Found_Part_One#} «<span>{$SearchedFor}</span>» {#Search_Not_Found_Part_Two#}.
                            </h2>
                            <h3 class="header-3">{#Search_Like#}</h3>
                            <ul>
                                <li>{#Search_Like_First#}</li>
                                <li>{#Search_Like_Second#}</li>
                                <li>{#Search_Like_Third#}</li>
                            </ul>
                        </div>
                    {else}
                        <div class="helpers">
                            <h2 class="subheader-2">
                                {#Search_SearchedFor#}: «<span>{$SearchedFor}</span>»
                            </h2>
                        </div>
                        <div class="summary">
                            <div class="results suggested-links"></div>
                            {if $SearchCategory == ''}
                                {include file='blocks/search_no_category.tpl'}
                            {elseif $SearchCategory == 'wowguild'}
                                {include file='blocks/search_wowguild.tpl'}
                            {elseif $SearchCategory == 'wowcharacter'}
                                {include file='blocks/search_wowcharacter.tpl'}
                            {elseif $SearchCategory == 'article'}
                                {include file='blocks/search_article.tpl'}
                            {elseif $SearchCategory == 'wowitem'}
                                {include file='blocks/search_items.tpl'}
                            {/if}
                        </div>
                    {/if}
                </div>
                <div class="search-left">
                    <div class="search-header">
                        <h2 class="header-2">
                            {#Search#}
                        </h2>
                    </div>
                    {if $SearchResults.foundtotal != 0}
                    <ul class="dynamic-menu" id="menu-search">
                        <li {if $SearchCategory == ''} class="item-active"{/if}>
                            <a href="/search?f=&amp;q={$SearchedFor}">
                                <span class="arrow">{#Search_All_Found#} ({$SearchResults.foundtotal})</span>
                            </a>
                        </li>


                        {if $SearchResults.guildsfound > 0}
                            <li {if $SearchCategory == 'wowguild'} class="item-active"{/if}>
                                <a href="/search?q={$SearchedFor}&amp;f=wowguild">
                                    <span class="arrow">{#Search_Guilds_Found#} ({$SearchResults.guilds|count})</span>
                                </a>

                            </li>
                        {/if}
                        {if $SearchResults.charactersfound > 0}
                            <li {if $SearchCategory == 'wowcharacter'} class="item-active"{/if}>
                                <a href="/search?f=wowcharacter&amp;q={$SearchedFor}">
                                    <span class="arrow">{#Search_Characters_Found#} ({$SearchResults.characters|count})</span>
                                </a>
                            </li>
                        {/if}
                        {if $SearchResults.itemsfound > 0}
                            <li {if $SearchCategory == 'wowitem'} class="item-active"{/if}>
                                <a href="/search?f=wowitem&amp;q={$SearchedFor}">
                                    <span class="arrow">{#Search_Items_Found#} ({$SearchResults.items|count})</span>
                                </a>
                            </li>
                        {/if}
                        {if $SearchResults.articlesfound > 0}
                            <li {if $SearchCategory == 'article'} class="item-active"{/if}>
                                <a href="/search?q={$SearchedFor}&amp;f=article">
                                    <span class="arrow">{#Search_Articles_Found#} ({$SearchResults.articles|count})</span>
                                </a>

                            </li>
                        {/if}
                    </ul>
                    {/if}
                </div>
                <span class="clear"></span>
            </div>
        </div>
    </div>
</div>
{include file="footer.tpl"}