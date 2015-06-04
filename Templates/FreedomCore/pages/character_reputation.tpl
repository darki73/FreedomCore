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
                    <a href="/character/{$Character.name}/reputation" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Profile_Character_Reputation#}</span>
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
                    <div class="reputation reputation-tabular" id="reputation">
                        <div class="profile-section-header">
                            <h3 class="category ">
                                {#Profile_Character_Reputation#}
                            </h3>
                        </div>
                        <div class="profile-section">
                            <div class="table" id="sortable">
                                <div class="data-container type-table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th><a href="#" class="sort-link"><span class="arrow up">{#Profile_Character_Reputation_Title#}</span></a></th>
                                                <th colspan="2"><a href="#" class="sort-link numeric"><span class="arrow">{#Profile_Character_Reputation_Status#}</span></a></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        {foreach $Reputations as $Reputation}
                                            {$DoMath = ($Reputation.site_reputation / $Reputation.max) * 100}
                                            <tr>
                                                <td>
                                                    <span class="faction-name">
                                                            {$Reputation.name_loc0}
                                                    </span>
                                                </td>
                                                <td class="rank-{$Reputation.rank}" data-raw="{if $DoMath == 100}{$Reputation.rank}.100{elseif $DoMath >= 10}{$Reputation.rank}.0{$DoMath|string_format:"%d"}{else}{$Reputation.rank}.00{$DoMath|string_format:"%d"}{/if}">
                                                    <div class="faction-standing">
                                                        <div class="faction-bar">
                                                            <div class="faction-score">
                                                                {$Reputation.site_reputation}/{$Reputation.max}
                                                            </div>
                                                            <div class="faction-fill" style="width: {$DoMath}%;"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="rank-{$Reputation.rank}" data-raw="{$Reputation.rank}">
                                                    <a href="javascript:;" data-fansite="faction|{$Reputation.faction}|{$Reputation.name_loc0}" class="fansite-link float-right"> </a>
                                                    <span class="faction-level">
                                                        {$Reputation.translation}
                                                    </span>
                                                </td>
                                            </tr>
                                        {/foreach}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <script type="text/javascript">
                                //<![CDATA[
                                $(function() {
                                    Reputation.table = DataSet.factory('#sortable', { column: 0, method: "default" });
                                    Reputation.table.config.articles = ['a','an','the'];
                                });
                                //]]>
                            </script>
                        </div>
                    </div>
                </div>
                <span class="clear"></span>
            </div>
            <script type="text/javascript">
                //<![CDATA[
                $(function() {
                    Profile.url = '/character/{$Character.name}/reputation';
                });

            </script>
        </div>
    </div>
</div>
{include file="footer.tpl"}