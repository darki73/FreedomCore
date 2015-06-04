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
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/character/{$Character.name}/profession" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Profile_Character_Professions#}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/character/{$Character.name}/profession/{$SelectedProfession}" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$ProfessionInfo.translation}</span>
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
                                    <li>
                                        <a href="/character/{$Character.name}" class="back-to" rel="np"><span class="arrow"><span class="icon">{#Profile_Character_Information#}</span></span></a>
                                    </li>
                                    <li class="">
                                        <span class="divider">{#Profile_Character_Professions#}</span>
                                    </li>
                                    {foreach $Professions as $Profession}
                                        {if $Profession.primary == 1}
                                            {if $Profession.name == $SelectedProfession}
                                            <li class="active">
                                            {else}
                                            <li>
                                            {/if}
                                                <a href="/character/{$Character.name}/profession/{$Profession.name}" class="" rel="np">
                                                <span class="arrow">
                                                    <span class="icon">
                                                        <span class="icon-frame frame-14 ">
                                                            <img src="/Templates/{$Template}/images/icons/tiny/trade_{$Profession.name}.gif" alt="" width="14" height="14" />
                                                        </span>
                                                        {$Profession.translation}
                                                    </span>
                                                </span>
                                                </a>
                                            </li>
                                        {/if}
                                    {/foreach}
                                    <li class="">
                                        <span class="divider">{#Profile_Character_SecondaryProfessions#}</span>
                                    </li>
                                    {foreach $Professions as $Profession}
                                        {if $Profession.primary == 0}
                                            {if $Profession.name == $SelectedProfession}
                                                <li class="active">
                                                    {else}
                                                <li>
                                            {/if}
                                            <a href="/character/{$Character.name}/profession/{$Profession.name}" class="" rel="np">
                                                <span class="arrow">
                                                    <span class="icon">
                                                        <span class="icon-frame frame-14 ">
                                                            <img src="/Templates/{$Template}/images/icons/tiny/trade_{$Profession.name}.gif" alt="" width="14" height="14" />
                                                        </span>
                                                        {$Profession.translation}
                                                    </span>
                                                </span>
                                            </a>
                                            </li>
                                        {/if}
                                    {/foreach}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="profile-contents">
                    <div id="profession-recipe-unknown" style="display: none">
                        {#Profile_Character_Profession_NotLearned#}
                    </div>
                    <div class="profile-section-header">
                        <div class="profession-rank">
                            <div class="profile-progress border-3 completed">
                                {$ProfessionBarLength = ($ProfessionInfo.current / $ProfessionInfo.max) * 100}
                                <div class="bar border-3 hover" style="width: {$ProfessionBarLength|string_format:"%d"}%"></div>
                                <div class="bar-contents">
                                    <a class="profession-details" href="/game/profession/{$SelectedProfession}">
                                        <span class="name">{$ProfessionInfo.title}</span>
                                        <span class="value">{$ProfessionInfo.current} / {$ProfessionInfo.max}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <h3 class="category ">
                            <a href="/game/profession/{$SelectedProfession}">
                                <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/tiny/trade_{$SelectedProfession}.gif&quot;);"></span>
                                {$ProfessionInfo.translation}
                            </a>
                        </h3>
                    </div>
                    {if $SelectedProfession == 'fishing'}
                        {include file="blocks/profession_fishing.tpl"}
                    {else}
                        <div class="profile-section">
                            {include file="blocks/profession_loot_list.tpl"}
                        </div>
                    {/if}
                </div>
                <span class="clear"></span>
            </div>
        </div>
    </div>
</div>
{include file="footer.tpl"}