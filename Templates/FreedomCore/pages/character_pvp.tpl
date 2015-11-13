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
                    <a href="/community/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Menu_Community#}</span>
                    </a>
                </li>
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/character/{$Character.name}" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$Character.name}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/character/{$Character.name}/pvp" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">PvP</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div id="profile-wrapper" class="profile-wrapper profile-wrapper-{$Character.side}">
                <div class="profile-sidebar-anchor">
                    <div class="profile-sidebar-outer">
                        <div class="profile-sidebar-inner">
                            <div class="profile-sidebar-contents">
                                <div class="profile-sidebar-crest">
                                    <a href="/character/{$Character.name}/" rel="np" class="profile-sidebar-character-model" style="background-image: url(/Templates/{$Template}/images/2d/inset/{$Character.race}-{$Character.gender}.jpg);">
                                        <span class="hover"></span>
                                        <span class="fade"></span>
                                    </a>
                                    <div class="profile-sidebar-info">
                                        <div class="name">
                                            <a href="/character/{$Character.name}/" rel="np">{$Character.name}</a>
                                        </div>
                                        <div class="under-name color-c{$Character.class}">
                                            <a href="/game/race/{$Character.race_data.name}" class="race">{$Character.race_data.translation}</a>-<a href="/game/class/{$Character.class_data.name}" class="class">{$Character.class_data.translation}</a> <span class="level"><strong>{$Character.level}</strong></span> {#LevelShort#}
                                        </div>
                                        {if $Character.guild_name != ''}
                                            <div class="guild">
                                                <a href="/guild/{$Character.guild_name}/?character={$Character.name}">{$Character.guild_name}</a>
                                            </div>
                                        {/if}
                                        <div class="realm">
                                            <span id="profile-info-realm" class="tip" data-battlegroup="">Realm Name</span>
                                        </div>
                                    </div>
                                </div>
                                <ul class="profile-sidebar-menu" id="profile-sidebar-menu">
                                    {include file='blocks/character_sidebar.tpl'}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="profile-contents">
                    <div class="profile-section-header">
                        <h3 class="category ">PvP</h3>
                    </div>
                    <div class="profile-section">
                        <div id="pvp-tabs" class="pvp-tabs">
                            <div class="tab" id="pvp-tab-bgs" data-id="bgs">
                                <span class="type">{#PvP_Rated_Battlegrounds#}</span>
                                <ul class="ratings">
                                    <li>
                                        <span class="value">0</span>
                                        <span class="name">{#PvP_Rating#}</span>
                                    </li>
                                </ul>
                            </div>
                            {if empty($ArenaRating)}
                                <div class="tab tab-disabled" id="pvp-tab-2v2" data-id="2v2">
                                    <span class="type">2 {#PvP_VS#} 2</span>
                                </div>
                                <div class="tab tab-disabled" id="pvp-tab-3v3" data-id="3v3">
                                    <span class="type">3 {#PvP_VS#} 3</span>
                                </div>
                                <div class="tab tab-disabled" id="pvp-tab-5v5" data-id="5v5">
                                    <span class="type">5 {#PvP_VS#} 5</span>
                                </div>
                            {else}
                                {assign 'BraketsPassed' ''}
                                {$AvailableBrackets = [2, 3, 5]}
                                {foreach from=$ArenaRating item=Rating key=i name=Rating}
                                    <div class="tab" id="pvp-tab-{$Rating.type}v{$Rating.type}" data-id="{$Rating.type}v{$Rating.type}">
                                        <span class="type">{$Rating.type} {#PvP_VS#} {$Rating.type}</span>

                                        <ul class="ratings">
                                            <li>
                                            <span class="rank">
                                                    <a href="/pvp/leaderboards/{$Rating.type}v{$Rating.type}">{#PvP_View_Rang#}</a>
                                            </span>
                                            </li>
                                            <li>
                                                <span class="arenateam-gameswon">{$Rating.weekWins}</span> –
                                                <span class="arenateam-gameslost">{$Rating.weekGames - $Rating.weekWins}</span>
                                                <span class="arenateam-percent">({(($Rating.weekWins/$Rating.weekGames)*100)|string_format:"%d"}%)</span>
                                            </li>
                                            <li>
                                                <span class="value">{$Rating.personalRating}</span>
                                                <span class="name">{#PvP_Rating#}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    {if !$smarty.foreach.Rating.last}
                                        {$BraketsPassed = $BraketsPassed|cat:$Rating.type|cat:','}
                                    {else}
                                        {$BraketsPassed = $BraketsPassed|cat:$Rating.type}
                                    {/if}
                                {/foreach}
                                {assign 'PassedArray' ','|explode:$BraketsPassed}
                                {$Differences = array_diff($AvailableBrackets, $PassedArray)}
                                {foreach $Differences as $Difference}
                                    <div class="tab tab-disabled" id="pvp-tab-{$Difference}v{$Difference}" data-id="{$Difference}v{$Difference}">
                                        <span class="type">{$Difference} {#PvP_VS#} {$Difference}</span>
                                    </div>
                                {/foreach}
                            {/if}
                            <span class="clear"></span>
                        </div>
                    </div>
                    <script type="text/javascript">
                        //<![CDATA[
                        $(document).ready(function() {
                            Pvp.initialize();
                        });
                        //]]>
                    </script>
                </div>
                <span class="clear"></span>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}