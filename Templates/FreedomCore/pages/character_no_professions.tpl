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
                    <a href="/character/{$Character.name}/profession" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Profile_Character_Professions#}</span>
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
                                        <a href="/character/{$Character.name}/" class="back-to" rel="np">
                                            <span class="arrow">
                                                <span class="icon">{#Profile_Character_Information#}</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <span class="divider">{#Profile_Character_Professions#}</span>
                                    </li>
                                    <li class="">
                                        <span class="indent empty">{#No#}</span>
                                    </li>
                                    <li class="">
                                        <span class="divider">{#Profile_Character_SecondaryProfessions#}</span>
                                    </li>
                                    {if !isset($Professions)}
                                        <li class=" disabled">
                                            <a href="javascript:;" class="" rel="np">
                                            <span class="arrow">
                                                <span class="icon">
                                                    <span class="icon-frame frame-14 ">
                                                        <img src="/Templates/{$Template}/images/icons/tiny/trade_archaeology.gif" alt="" width="14" height="14" />
                                                    </span>
                                                    {#Character_Professions_Archaeology#}
                                                </span>
                                            </span>
                                            </a>
                                        </li>
                                        <li class=" disabled">
                                            <a href="javascript:;" class="" rel="np">
                                            <span class="arrow">
                                                <span class="icon">
                                                    <span class="icon-frame frame-14 ">
                                                        <img src="/Templates/{$Template}/images/icons/tiny/trade_cooking.gif" alt="" width="14" height="14" />
                                                    </span>
                                                    {#Character_Professions_Cooking#}
                                                </span>
                                            </span>
                                            </a>
                                        </li>
                                        <li class=" disabled">
                                            <a href="javascript:;" class="" rel="np">
                                            <span class="arrow">
                                                <span class="icon">
                                                    <span class="icon-frame frame-14 ">
                                                        <img src="/Templates/{$Template}/images/icons/tiny/trade_first-aid.gif" alt="" width="14" height="14" />
                                                    </span>
                                                    {#Character_Professions_First_aid#}
                                                </span>
                                            </span>
                                            </a>
                                        </li>
                                        <li class=" disabled">
                                            <a href="javascript:;" class="" rel="np">
                                            <span class="arrow">
                                                <span class="icon">
                                                    <span class="icon-frame frame-14 ">
                                                        <img src="/Templates/{$Template}/images/icons/tiny/trade_fishing.gif" alt="" width="14" height="14" />
                                                    </span>
                                                    {#Character_Professions_Fishing#}
                                                </span>
                                            </span>
                                            </a>
                                        </li>
                                    {else}
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
                                    {/if}
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
                        <h3 class="category ">			{#Profile_Character_Professions#}
                        </h3>
                    </div>

                    <div class="profile-section no-profession">
                        <h2>{#Profile_Character_Profession_OhWell#}</h2>
                        <p>{#Profile_Character_Profession_GoLearn#}</p>
                    </div>


                </div>
                <span class="clear"></span>
            </div>
        </div>
    </div>
</div>
{include file="footer.tpl"}