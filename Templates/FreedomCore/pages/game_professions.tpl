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
                    <a href="/game/profession" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Profile_Character_Professions#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div id="wiki" class="wiki directory wiki-profession">
                <div class="title">
                    <h2>
                        {#Profile_Character_Professions#}
                    </h2>
                    <div class="desc">
                        {#Game_Professions_Header_Description#}
                    </div>
                </div>
                <div class="grid-view">
                    <div class="grid-title">
                        <h2 class="header ">{#Game_Professions_Primary#}</h2>
                    </div>
                    {foreach from=$Professions item=Profession key=i}
                        {if $Profession.is_primary == 1}
                            <a  href="/game/profession/{$Profession.profession_name}" class="grid {$Profession.profession_name} {if $i%2==1}{else}end{/if}">
                                <strong>{$Profession.profession_translation}</strong>
                                <span>{$Profession.profession_description}</span>
                            </a>
                        {/if}
                    {/foreach}
                    <span class="clear"></span>
                    <div class="grid-title">
                        <h2 class="header ">{#Game_Professions_Secondary#}</h2>
                    </div>
                    {foreach from=$Professions item=Profession key=i}
                        {if $Profession.is_primary == 0}
                            <a  href="/game/profession/{$Profession.profession_name}" class="grid {$Profession.profession_name} {if $i%2==1}end{/if}">
                                <strong>{$Profession.profession_translation}</strong>
                                <span>{$Profession.profession_description}</span>
                            </a>
                        {/if}
                    {/foreach}
                    <span class="clear"></span>
                </div>
                <span class="clear"></span>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}