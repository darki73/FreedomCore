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
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/zone/{$ZoneInfo.link_name}/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$ZoneInfo.name}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/zone/{$ZoneInfo.link_name}/{$BossInfo.boss_link}" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$BossInfo.name}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div id="wiki" class="wiki wiki-boss">
                <div class="sidebar">
                    <div class="snippet">
                        <div class="model-viewer">
                            <div class="model  can-drag" id="model-{$BossInfo.entry}">
                                <div class="loading">
                                    <div class="viewer" style="background-image: url('/Uploads/Core/NPC/ModelViewer/creature{$BossInfo.entry}.jpg');"></div>
                                </div>
                                <a href="javascript:;" class="zoom"></a>
                                <a href="javascript:;" class="rotate"></a>
                            </div>
                            <script type="text/javascript">
                                //<![CDATA[
                                $(function() {
                                    Npc.models = {
                                        "{$BossInfo.entry}": ModelRotator.factory("#model-{$BossInfo.entry}")
                                    };
                                });
                                //]]>
                            </script>
                        </div>
                    </div>
                    <div class="snippet">
                        <h3>{#Item_Info_Interesting_Fact#}</h3>
                        <ul class="fact-list">
                            <li>
                                <span class="term">{#Zones_Level#}:</span>
                                {assign 'CreatureMaxLevel' '0'}
                                {assign 'CreatureMaxHP' '0'}
                                {if $NPC.difficulty_entry_3 != 0}
                                    {$CreatureMaxLevel = $NPC.difficulty_entry_3.maxlevel}
                                    {$CreatureMaxHP = $NPC.difficulty_entry_3.health}
                                {elseif $NPC.difficulty_entry_2 != 0}
                                    {$CreatureMaxLevel = $NPC.difficulty_entry_2.maxlevel}
                                    {$CreatureMaxHP = $NPC.difficulty_entry_2.health}
                                {elseif $NPC.difficulty_entry_1 != 0}
                                    {$CreatureMaxLevel = $NPC.difficulty_entry_1.maxlevel}
                                    {$CreatureMaxHP = $NPC.difficulty_entry_1.health}
                                {/if}
                                {if $NPC.minlevel == 83}
                                    {$NPC.minlevel} {#NPC_Boss#}
                                {else}
                                    {if $CreatureMaxLevel != 0}
                                        {$NPC.minlevel}–{$CreatureMaxLevel}
                                    {else}
                                        {$NPC.minlevel}
                                    {/if}
                                    {if $NPC.rank == 1}
                                        {#NPC_Elite#}
                                    {/if}
                                    {if $NPC.difficulty_entry_1 != 0 || $NPC.difficulty_entry_2 != 0 || $NPC.difficulty_entry_3 != 0}
                                        (<span class="color-yellow">{$CreatureMaxLevel}</span> {#Zones_Heroic#})
                                    {/if}
                                {/if}
                            </li>
                            <li>
                                <span class="term">{#NPC_Health#}:</span>
                                {if $CreatureMaxHP != 0}
                                    {$NPC.health}–{$CreatureMaxHP}
                                {else}
                                    {$NPC.health}
                                {/if}
                                {if $NPC.difficulty_entry_1 != 0 || $NPC.difficulty_entry_2 != 0 || $NPC.difficulty_entry_3 != 0}
                                    (<span class="color-yellow">{$CreatureMaxHP}</span> {#Zones_Heroic#})
                                {/if}
                            </li>
                            <li>
                                <span class="term">{#Type#}:</span>
                                {$NPC.type.translation}
                            </li>
                        </ul>
                    </div>
                    <div class="snippet">
                        <h3>{#Game_Learn_More#}</h3>
                        <span id="fansite-links" class="fansite-group"></span>
                        <script type="text/javascript">
                            //<![CDATA[
                            $(function() {
                                Fansite.generate('#fansite-links', "npc|{$BossInfo.entry}|{$BossInfo.name}");
                            });
                            //]]>
                        </script>
                    </div>
                </div>
                <div class="info">
                    <div class="title">
                        <span class="parent">
                            <a href="/zone/{$ZoneInfo.link_name}/" data-zone="{$ZoneInfo.zone}">{$ZoneInfo.name}</a>
                        </span>
                        <h2>
                            {$BossInfo.name}
                        </h2>

                    </div>
                </div>
                <span class="clear"></span>
                <div class="related">
                    <div class="tabs">
                        <ul id="related-tabs">
                            <li>
                                <a href="#loot" data-key="loot" id="tab-loot" class="tab-active">
								    <span><span>
								        {#Raids_Loot#}
                                        </span></span>
                                </a>
                            </li>
                            <li>
                                <a href="#achievements" data-key="achievements" id="tab-achievements" class="tab-active">
										<span><span>
												{#Profile_Character_Achievements#}
										</span></span>
                                </a>
                            </li>
                        </ul>
                        <span class="clear"></span>
                    </div>
                    <div id="related-content" class>

                    </div>
                </div>
                <script type="text/javascript">
                    //<![CDATA[
                    $(function() {
                        Wiki.pageUrl = '/zone/{$ZoneInfo.link_name}/{$BossInfo.boss_link}/';
                    });
                    //]]>
                </script>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}