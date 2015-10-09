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
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/guild/{$Guild.name}" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$Guild.name}</span>
                    </a>
                </li>
            </ol>
        </div>

        <div class="content-bot clear">
            <div id="profile-wrapper" class="profile=wrapper profile-wrapper-{$Guild.guild_side.name}">
                <div class="profile-sidebar-anchor">
                    <div class="profile-sidebar-outer">
                        <div class="profile-sidebar-inner">
                            <div class="profile-sidebar-contents">
                                <div class="profile-info-anchor profile-guild-info-anchor">
                                    <div class="guild-tabard">
                                        <canvas id="guild-tabard" width="240" height="240" style="opacity:1;">
                                            <div class="guild-tabard-default tabard-{$Guild.guild_side.name}"></div>
                                        </canvas>
                                        <script type="text/javascript">
                                            //<![CDATA[
                                            $(document).ready(function() {
                                                var tabard = new GuildTabard('guild-tabard', {
                                                    'ring': '{$Guild.guild_side.name}',
                                                    'bg': [ 0, {$Guild.BackgroundColor} ],
                                                    'border': [ {$Guild.BorderStyle}, {$Guild.BorderColor} ],
                                                    'emblem': [ {$Guild.EmblemStyle}, {$Guild.EmblemColor} ]
                                                });
                                            });
                                            //]]>
                                        </script>
                                    </div>
                                    <div class="profile-info profile-guild-info">
                                        <div class="name"><a href="/guild/{$Guild.name}">{$Guild.name}</a></div>
                                        <div class="under-name">
                                            (<span class="faction">{$Guild.guild_side.translation}</span>)<span class="comma">,</span>

                                            <span class="members">{#Guild_Population#} {$Guild.guild_population}</span>
                                        </div>

                                    </div>
                                </div>
                                <ul class="profile-sidebar-menu" id="profile-sidebar-menu">
                                    {if $returnto != false}
                                        <li class="">
                                            <a href="/character/{$returnto}/" class="back-to" rel="np"><span class="arrow"><span class="icon">{$returnto}</span></span></a>
                                        </li>
                                    {/if}
                                    <li class=" active">
                                        <a href="/guild/{$Guild.name}/{if $returnto != false}?character={$returnto}{/if}" class="" rel="np">
                                            <span class="arrow">
                                                <span class="icon">
                                                    {#Guild_Main_Text#}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    
                                    <li class="">
                                        <a href="/guild/{$Guild.name}/roster{if $returnto != false}?character={$returnto}{/if}" class="" rel="np">
                                            <span class="arrow">
                                                <span class="icon">
                                                    {#Guild_Roster#}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="/guild/{$Guild.name}/news{if $returnto != false}?character={$returnto}{/if}" class="" rel="np">
                                            <span class="arrow">
                                                <span class="icon">
                                                    {#Guild_News#}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class=" disabled">
                                        <a href="javascript:;" class=" vault" rel="np">
                                            <span class="arrow">
                                                <span class="icon">
                                                    {#Guild_Events#}
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}