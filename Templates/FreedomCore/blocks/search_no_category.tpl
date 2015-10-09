{if $SearchResults.charactersfound > 0}
<div class="results results-grid wow-results">
    <h3 class="header-3">
        <a href="?q={$SearchedFor}&amp;f=wowcharacter">{#Search_Characters_Found#}</a> ({$SearchResults.characters|count})
    </h3>
    {foreach $SearchResults.characters as $Character}
        {if $smarty.foreach.$Character.index == 2}
            {break}
        {/if}
        <div class="grid">
            <div class="wowcharacter">
                <a href="/character/{$Character.name}/" class="icon-frame frame-56 thumbnail">
                    <img src="/Templates/{$Template}/images/2d/avatar/{$Character.race}-{$Character.gender}.jpg" alt="" width="56" height="56" />
                </a>
                <a href="/character/{$Character.name}/" class="color-c{$Character.class}">
                    <strong>{$Character.name}</strong>
                </a><br />
                {$Character.class_name}-{$Character.race_name} {$Character.level} {#LevelShort#}<br />
                Realm Name
                <span class="clear"><!-- --></span>
            </div>
        </div>
    {/foreach}
    <span class="clear"><!-- --></span>
</div>
{/if}

{if $SearchResults.itemsfound > 0}
    <div class="results results-grid wow-results">
        <h3 class="header-3">
            <a href="?f=wowitem&amp;q={$SearchedFor}">{#Search_Items_Found#}</a>
            ({$SearchResults.items|count})
        </h3>
        {assign 'LoopedItems' 0}
        {foreach $SearchResults.items as $Item}
            {if $LoopedItems == 3}
                {break}
            {/if}
            <div class="grid">
                <div class="wowitem">
                    <a href="/item/{$Item.id}" class="thumbnail" data-item="{$Item.id}">
                    <span class="icon-frame frame-32 ">
                        <img src="/Templates/{$Template}/images/icons/medium/{$Item.icon}.jpg" alt="" width="32" height="32" />
                    </span>
                    </a>
                    <a href="/item/{$Item.id}" class="color-q{$Item.Quality}" data-item="{$Item.id}">
                        <strong>{$Item.name}</strong>
                    </a><br />
                    {#Item_ItemLevel#} {$Item.ItemLevel}
                    <span class="clear"><!-- --></span>
                </div>
            </div>
            {$LoopedItems = $LoopedItems + 1}
        {/foreach}
        <span class="clear"><!-- --></span>
    </div>
{/if}

{if $SearchResults.guildsfound > 0}
    <div class="results results-grid wow-results">
        <h3 class="header-3">
            <a href="?q={$SearchedFor}&amp;f=wowguild">{#Search_Guilds_Found#}</a> ({$SearchResults.guilds|count})
        </h3>

        {foreach $SearchResults.guilds as $Guild}
            {if $smarty.foreach.$Guild.index == 2}
                {break}
            {/if}
            <div class="grid">
                <div class="wowguild">
                    <canvas id="tabard-{$Guild.guid}" class="thumbnail" width="32" height="32" style="opacity: 1;"></canvas>
                    <a href="/guild/{$Guild.name}/" class="sublink">
                        <strong>{$Guild.name}</strong>
                    </a>
                    - {$Guild.side_translation}<br />

                    Realm Name

                    <script type="text/javascript">
                        //<![CDATA[
                        $(function() {
                            var tabard{$Guild.guid} = new GuildTabard('tabard-{$Guild.guid}', {
                                ring: '{$Guild.side}',
                                'bg': [ 0, {$Guild.BackgroundColor} ],
                                'border': [ {$Guild.BorderStyle}, {$Guild.BorderColor} ],
                                'emblem': [ {$Guild.EmblemStyle}, {$Guild.EmblemColor} ]
                            });
                        });
                        //]]>
                    </script>
                    <span class="clear"><!-- --></span>
                </div>
            </div>
        {/foreach}
        <span class="clear"><!-- --></span>
    </div>
{/if}

{if $SearchResults.articlesfound > 0}
    <div class="results results-grid article-results">
        <h3 class="header-3">
            <a href="?q={$SearchedFor}&amp;f=article">{#Search_Articles_Found#}</a> ({$SearchResults.articles|count})
        </h3>
        {foreach $SearchResults.articles as $Article}
        {if $smarty.foreach.$Article.index == 2}
            {break}
        {/if}
        <div class="grid">
            <div class="article">
                <a href="/blog/{$Article.id}" class="article-thumbnail">
                    <span style="background-image: url('/Uploads/{$Article.miniature}'); height: 32px; width:32px;" alt=""></span>
                </a>
                <a href="/blog/{$Article.id}">
                    <strong>{$Article.title}</strong>
                </a><br />
                {$Article.comments} комментариев
                <span class="clear"><!-- --></span>
            </div>
        </div>
        {/foreach}

        <span class="clear"><!-- --></span>
    </div>
{/if}