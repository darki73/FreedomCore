{include file="header.tpl"}
<div id="content">
    <div class="content-top body-top">
        <div class="content-trail">
            {if $ZoneInfo.instance_type.type >= 3}
                {assign 'TooltipTypeLink' 'raids'}
            {else}
                {assign 'TooltipTypeLink' 'dungeons'}
            {/if}
            <ol class="ui-breadcrumb">
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">World of Warcraft</span>
                    </a>
                </li>
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/game/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">Игра</span>
                    </a>
                </li>
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/zone/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Zones_InstancesRaidsCMs#}</span>
                    </a>
                </li>
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/zone/#expansion={$ZoneInfo.expansion_required.expansion}&amp;type={$TooltipTypeLink}" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$ZoneInfo.expansion_required.translation}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/zone/{$ZoneInfo.link_name}/" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$ZoneInfo.name}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div id="wiki" class="wiki wiki-zone">
                <div class="sidebar">
                    <table class="media-frame">
                        <tr>
                            <td class="tl"></td>
                            <td class="tm"></td>
                            <td class="tr"></td>
                        </tr>
                        <tr>
                            <td class="ml"></td>
                            <td class="mm">
                                <a href="javascript:;" class="thumbnail" onclick="Lightbox.loadImage([{ src: '/Templates/{$Template}/images/wiki/zone/screenshots/{$ZoneInfo.link_name}.jpg' }]);">
                                    <span class="view"></span>
                                    <img src="/Templates/{$Template}/images/wiki/zone/thumbnails/{$ZoneInfo.link_name}.jpg" width="265" alt="" />
                                </a>
                            </td>
                            <td class="mr"></td>
                        </tr>
                        <tr>
                            <td class="bl"></td>
                            <td class="bm"></td>
                            <td class="br"></td>
                        </tr>
                    </table>
                    <div class="snippet">
                        <h3>{#Item_Info_Interesting_Fact#}</h3>
                        <ul class="fact-list">
                            <li>
                                <span class="term">{#Type#}:</span>
                                {$ZoneInfo.instance_type.translation}
                            </li>
                            <li>
                                <span class="term">{#Zones_Players#}:</span>
                                {if $ZoneInfo.min_players == $ZoneInfo.max_players}
                                    {$ZoneInfo.min_players}
                                {else}
                                    {$ZoneInfo.min_players}/{$ZoneInfo.max_players}
                                {/if}
                            </li>
                            <li>
                                <span class="term">{#Zones_Level#}:</span>
                                {if $ZoneInfo.min_level == $ZoneInfo.max_level}
                                    {$ZoneInfo.max_level}
                                {else}
                                    {$ZoneInfo.min_level}-{$ZoneInfo.max_level} {if $ZoneInfo.heroic_possible}{if $ZoneInfo.max_level != $ZoneInfo.heroic_level_required} (<span class="color-yellow">{$ZoneInfo.heroic_level_required}</span> {#Zones_Heroic#}){/if}{/if}
                                {/if}
                            </li>
                            <li>
                                <span class="term">{#Zones_Place#}:</span>
                                {$ZoneInfo.zone_name}
                            </li>
                            {if $ZoneInfo.heroic_possible}
                                <li>{#Zones_AvailableInHeroic#}<span class="icon-heroic-skull"></span></li>
                            {/if}
                        </ul>
                    </div>
                    <div class="snippet">
                        <h3>{#Zones_Map#}</h3>
                        <table class="media-frame">
                            <tr>
                                <td class="tl"></td>
                                <td class="tm"></td>
                                <td class="tr"></td>
                            </tr>
                            <tr>
                                <td class="ml"></td>
                                <td class="mm">
                                    <a href="javascript:;" id="map-floors" class="thumbnail" style="background: url(/Templates/{$Template}/images/dungeon-maps/{$LanguageStyle}/{$ZoneInfo.link_name}/{$ZoneInfo.link_name}.jpg) 0px 0px no-repeat;">
                                        <span class="view"></span>
                                    </a>
                                </td>
                                <td class="mr"></td>
                            </tr>
                            <tr>
                                <td class="bl"></td>
                                <td class="bm"></td>
                                <td class="br"></td>
                            </tr>
                        </table>
                        <script type="text/javascript">
                            //<![CDATA[
                            $(function() {
                                Zone.floors = [
                                    {
                                        src: '/Templates/{$Template}/images/dungeon-maps/{$LanguageStyle}/{$ZoneInfo.link_name}/{$ZoneInfo.link_name}1-large.jpg'
                                    }
                                ];
                            });
                            //]]>
                        </script>
                    </div>
                    <div class="snippet">
                        <h3>{#Game_Learn_More#}</h3>

                        <span id="fansite-links" class="fansite-group">
                        <script type="text/javascript">
                            //<![CDATA[
                            $(function() {
                                Fansite.generate('#fansite-links', "zone|{$ZoneInfo.zone}|{$ZoneInfo.name}");
                            });
                            //]]>
                        </script>
                    </div>
                </div>
                <div class="info">
                    <div class="title">
                        <h2>{$ZoneInfo.name}</h2>
                        {if $ZoneInfo.expansion_required.expansion != 0}

                        {/if}
                        <span class="expansion-name color-ex{$ZoneInfo.expansion_required.expansion}">
                                {#Expansion_Required#} {$ZoneInfo.expansion_required.translation}
                        </span>
                    </div>
                    <p class="intro">
                        {$ZoneInfo.zone_description}
                    </p>
                    <div class="panel">
                        <div class="panel-title">{#Raids_Bosses#}</div>
                        <div class="zone-bosses">
                            {assign 'TotalBosses' count($ZoneInfo.bosses)}
                            <div class="boss-column-portrait">
                                {foreach from=$ZoneInfo.bosses item=Boss key=i}
                                    {if $i%2!=1}
                                        <div class="boss-avatar">
                                            <a href="/zone/{$ZoneInfo.link_name}/{$Boss.boss_link}" data-npc="{$Boss.entry}">
                                                <span class="boss-portrait" style="background-image: url('/Uploads/Core/NPC/Cache/creature{$Boss.entry}.jpg');">
                                                </span>
                                                <span class="boss-details">
                                                    <div class="boss-name">
                                                        {$Boss.name}
                                                    </div>
                                                </span>
                                            </a>
                                        </div>
                                    {/if}
                                {/foreach}
                            </div>
                            <div class="boss-column-portrait">
                            {foreach from=$ZoneInfo.bosses item=Boss key=i}
                                {if $i%2==1}
                                    <div class="boss-avatar">
                                        <a href="/zone/{$ZoneInfo.link_name}/{$Boss.boss_link}" data-npc="{$Boss.entry}">
                                                <span class="boss-portrait" style="background-image: url('/Uploads/Core/NPC/Cache/creature{$Boss.entry}.jpg');">
                                                </span>
                                                <span class="boss-details">
                                                    <div class="boss-name">
                                                        {$Boss.name}
                                                    </div>
                                                </span>
                                        </a>
                                    </div>
                                {/if}
                            {/foreach}
                            </div>
                        </div>
                        <span class="clear"></span>
                    </div>
                </div>
                <span class="clear"></span>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}