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
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/zone" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Zones_InstancesRaidsCMs#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div id="wiki" class="wiki directory wiki-zone">
                <div class="title">
                    <h2>{#Zones_InstancesRaidsCMs#}</h2>
                    <div class="desc">{#Zones_InstancesRaidsCMs_Desc#}</div>
                </div>
                <div class="wrapper">
                    <div class="groups">
                        {for $i = 0; $i <= 5;$i++}
                            {if $i == 0}
                                <div class="group" id="expansion-{$i}" style="display: block;">
                                    <div class="group-column dungeon">
                                        <h3>{#Zones_Instances#}</h3>
                                        <ul>
                                            {foreach $Instances as $Instance}
                                                {if $i == $Instance.expansion_required}
                                                    {if $Instance.instance_type.type == 1 || $Instance.instance_type.type == 2}
                                                        <li>
                                                            <a href="/zone/{$Instance.link_name}/" data-zone="{$Instance.zone}">
                                                                <span class="zone-thumbnail thumb-{$Instance.link_name}"></span>
                                                        <span class="level-range">
                                                                {if $Instance.min_level != $Instance.max_level}
                                                                    {$Instance.min_level} - {$Instance.max_level}
                                                                {else}
                                                                    {$Instance.max_level}
                                                                {/if}
                                                        </span>

                                                        <span class="name">
                                                            {$Instance.name}
                                                            {if $Instance.heroic_possible == 1}
                                                                <span class="icon-heroic-skull"></span>
                                                            {/if}
                                                        </span>
                                                                <span class="clear"><!-- --></span>
                                                            </a>
                                                        </li>
                                                    {/if}
                                                {/if}
                                            {/foreach}
                                        </ul>
                                    </div>
                                    <div class="group-column raid">
                                        <h3>{#Zones_Raids#}</h3>
                                        <ul>
                                            {foreach $Instances as $Instance}
                                                {if $i == $Instance.expansion_required}
                                                    {if $Instance.instance_type.type > 2 && $Instance.instance_type.type <= 10}
                                                        <li>
                                                            <a href="/zone/{$Instance.link_name}/" data-zone="{$Instance.zone}">
                                                                <span class="zone-thumbnail thumb-{$Instance.link_name}"></span>
                                                        <span class="level-range">
                                                                {if $Instance.min_level != $Instance.max_level}
                                                                    {$Instance.min_level} - {$Instance.max_level}
                                                                {else}
                                                                    {$Instance.max_level}
                                                                {/if}
                                                        </span>

                                                        <span class="name">
                                                            {$Instance.name}
                                                            {if $Instance.heroic_possible == 1}
                                                                <span class="icon-heroic-skull"></span>
                                                            {/if}
                                                        </span>
                                                                <span class="clear"><!-- --></span>
                                                            </a>
                                                        </li>
                                                    {/if}
                                                {/if}
                                            {/foreach}
                                        </ul>
                                    </div>
                                    <span class="clear"><!-- --></span>
                                </div>
                            {else}
                                <div class="group" id="expansion-{$i}" style="display: none;">
                                    <div class="group-column dungeon">
                                        <h3>{#Zones_Instances#}</h3>
                                        <ul>
                                            {assign 'PreviousGroupNumber' 0}
                                            {foreach from=$Instances item=Instance key=z}
                                                {if $i == $Instance.expansion_required}
                                                    {if $Instance.in_group == 0}
                                                        {if $Instance.instance_type.type == 1 || $Instance.instance_type.type == 2}
                                                            <li>
                                                                <a href="/zone/{$Instance.link_name}/" data-zone="{$Instance.zone}">
                                                                    <span class="zone-thumbnail thumb-{$Instance.link_name}"></span>
                                                                    <span class="level-range">
                                                                            {if $Instance.min_level != $Instance.max_level}
                                                                                {$Instance.min_level} - {$Instance.max_level}
                                                                            {else}
                                                                                {$Instance.max_level}
                                                                            {/if}
                                                                    </span>

                                                                    <span class="name">
                                                                        {$Instance.name}
                                                                        {if $Instance.heroic_possible == 1}
                                                                            <span class="icon-heroic-skull"></span>
                                                                        {/if}
                                                                    </span>
                                                                    <span class="clear"><!-- --></span>
                                                                </a>
                                                            </li>
                                                        {/if}
                                                    {else}
                                                        {if $Instance.in_group != $PreviousGroupNumber}
                                                            <li>
                                                                <span class="separator">{$Instance.group_name}</span>
                                                            </li>
                                                            {$PreviousGroupNumber = $Instance.in_group}
                                                            {if $Instance.instance_type.type == 1 || $Instance.instance_type.type == 2}
                                                                <li>
                                                                    <a href="/zone/{$Instance.link_name}/" data-zone="{$Instance.zone}">
                                                                        <span class="zone-thumbnail thumb-{$Instance.link_name}"></span>
                                                                    <span class="level-range">
                                                                            {if $Instance.min_level != $Instance.max_level}
                                                                                {$Instance.min_level} - {$Instance.max_level}
                                                                            {else}
                                                                                {$Instance.max_level}
                                                                            {/if}
                                                                    </span>

                                                                    <span class="name">
                                                                        {$Instance.name}
                                                                        {if $Instance.heroic_possible == 1}
                                                                            <span class="icon-heroic-skull"></span>
                                                                        {/if}
                                                                    </span>
                                                                        <span class="clear"><!-- --></span>
                                                                    </a>
                                                                </li>
                                                            {/if}
                                                        {else}
                                                            {if $Instance.instance_type.type == 1 || $Instance.instance_type.type == 2}
                                                                <li>
                                                                    <a href="/zone/{$Instance.link_name}/" data-zone="{$Instance.zone}">
                                                                        <span class="zone-thumbnail thumb-{$Instance.link_name}"></span>
                                                                    <span class="level-range">
                                                                            {if $Instance.min_level != $Instance.max_level}
                                                                                {$Instance.min_level} - {$Instance.max_level}
                                                                            {else}
                                                                                {$Instance.max_level}
                                                                            {/if}
                                                                    </span>

                                                                    <span class="name">
                                                                        {$Instance.name}
                                                                        {if $Instance.heroic_possible == 1}
                                                                            <span class="icon-heroic-skull"></span>
                                                                        {/if}
                                                                    </span>
                                                                        <span class="clear"><!-- --></span>
                                                                    </a>
                                                                </li>
                                                            {/if}
                                                        {/if}
                                                    {/if}
                                                {/if}
                                            {/foreach}
                                        </ul>
                                    </div>
                                    <div class="group-column raid">
                                        <h3>{#Zones_Raids#}</h3>
                                        <ul>
                                            {foreach $Instances as $Instance}
                                                {if $i == $Instance.expansion_required}
                                                    {if $Instance.instance_type.type > 2 && $Instance.instance_type.type <= 10}
                                                        <li>
                                                            <a href="/zone/{$Instance.link_name}/" data-zone="{$Instance.zone}">
                                                                <span class="zone-thumbnail thumb-{$Instance.link_name}"></span>
                                                        <span class="level-range">
                                                                {if $Instance.min_level != $Instance.max_level}
                                                                    {$Instance.min_level} - {$Instance.max_level}
                                                                {else}
                                                                    {$Instance.max_level}
                                                                {/if}
                                                        </span>

                                                        <span class="name">
                                                            {$Instance.name}
                                                            {if $Instance.heroic_possible == 1}
                                                                <span class="icon-heroic-skull"></span>
                                                            {/if}
                                                        </span>
                                                                <span class="clear"><!-- --></span>
                                                            </a>
                                                        </li>
                                                    {/if}
                                                {/if}
                                            {/foreach}
                                        </ul>
                                    </div>
                                    <span class="clear"><!-- --></span>
                                </div>
                            {/if}
                        {/for}
                    </div>
                    <ul class="navigation">
                        {*<li>*}
                            {*<a href="javascript:;" id="nav-5" onclick="WikiDirectory.view(this, 5);" class="expansion-5">*}
                                {*Warlords of Draenor*}
                            {*</a>*}
                        {*</li>*}
                        {*<li>*}
                            {*<a href="javascript:;" id="nav-4" onclick="WikiDirectory.view(this, 4);" class="expansion-4">*}
                                {*Mists of Pandaria*}
                            {*</a>*}
                        {*</li>*}
                        {*<li>*}
                            {*<a href="javascript:;" id="nav-3" onclick="WikiDirectory.view(this, 3);" class="expansion-3">*}
                                {*Cataclysm*}
                            {*</a>*}
                        {*</li>*}
                        <li>
                            <a href="javascript:;" id="nav-2" onclick="WikiDirectory.view(this, 2);" class="expansion-2">
                                Wrath of the Lich King
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" id="nav-1" onclick="WikiDirectory.view(this, 1);" class="expansion-1">
                                The Burning Crusade
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" id="nav-0" onclick="WikiDirectory.view(this, 0);" class="expansion-0 nav-active">
                                Classic
                            </a>
                        </li>
                    </ul>
                </div>
                <script type="text/javascript">
                    //<![CDATA[
                    $(function() {
                        WikiDirectory.initialize(0);
                    });
                    //]]>
                </script>
                <span class="clear"><!-- --></span>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}