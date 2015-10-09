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
                    <a href="/character/{$Character.name}/achievement" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Profile_Character_Achievements#}</span>
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
                                        <a href="/character/{$Character.name}/" class="back-to" rel="np"><span class="arrow"><span class="icon">{#Profile_Character_Information#}</span></span></a>
                                    </li>
                                    <li class="root-menu">
                                        <a href="/character/{$Character.name}/achievement" class="back-to" rel="np"><span class="arrow"><span class="icon">{#Profile_Character_Achievements#}</span></span></a>
                                    </li>
                                    <li class=" active">
                                        <a href="/character/{$Character.name}/achievement#summary" class="active" rel="np">
                                            <span class="arrow"><span class="icon">
                                                {#Profile_Character_Achievements#}
                                            </span></span>
                                        </a>
                                    </li>
                                    {foreach $Categories as $Category}
                                        {if $Category.id != 1}
                                            {if !isset($Category.subcategories)}
                                                <li class="">
                                                    <a href="/character/{$Character.name}/achievement#{$Category.id}" class="" rel="np">
                                                    <span class="arrow">
                                                        <span class="icon">
                                                            {$Category.name}
                                                        </span>
                                                    </span>
                                                    </a>
                                                </li>
                                            {else}
                                                <li class>
                                                    <a href="/character/{$Character.name}/achievement#{$Category.id}" class=" has-submenu" rel="np">
                                                        <span class="arrow">
                                                        <span class="icon">
                                                            {$Category.name}
                                                        </span>
                                                    </span>
                                                    </a>
                                                    <ul>
                                                        {foreach $Category.subcategories as $Subcat}
                                                            <li class>
                                                                <a href="/wow/ru/character/kazzak/Russianfur/achievement#{$Category.id}:{$Subcat.id}" class="" rel="np">
                                                                    <span class="arrow"><span class="icon">
                                                                        {$Subcat.name}
                                                                    </span></span>
                                                                </a>
                                                            </li>
                                                        {/foreach}
                                                    </ul>
                                                </li>
                                            {/if}
                                        {/if}
                                    {/foreach}
                                </ul>
                                <div id="swipe-in-container" class="swipe-container" style="display: none;">
                                    <ul class="profile-sidebar-menu">
                                        <li>
                                            <a class="back-to" href="/character/{$Character.name}/">
                                                <span class="arrow">
                                                    <span class="icon">
                                                        {#Profile_Character_Information#}
                                                    </span>
                                                </span>
                                            </a>
                                        </li>
                                        <ul class="profile-sidebar-menu" id="profile-sidebar-menu" style="display: none;">
                                            <li>
                                                <a class="back-to" href="/character/{$Character.name}/">
                                                <span class="arrow">
                                                    <span class="icon">
                                                        {#Profile_Character_Information#}
                                                    </span>
                                                </span>
                                                </a>
                                            </li>
                                            <li class="root-menu">
                                                <a href="/character/{$Character.name}/achievement" class="back-to" rel="np"><span class="arrow"><span class="icon">{#Profile_Character_Achievements#}</span></span></a>
                                            </li>
                                            {foreach $Categories as $Category}
                                                {if $Category.id != 1}
                                                    {if !isset($Category.subcategories)}
                                                        <li class="">
                                                            <a href="/character/{$Character.name}/achievement#{$Category.id}" class="" rel="np">
                                                    <span class="arrow">
                                                        <span class="icon">
                                                            {$Category.name}
                                                        </span>
                                                    </span>
                                                            </a>
                                                        </li>
                                                    {else}
                                                        <li class>
                                                            <a href="/character/{$Character.name}/achievement#{$Category.id}" class=" has-submenu" rel="np">
                                                        <span class="arrow">
                                                        <span class="icon">
                                                            {$Category.name}
                                                        </span>
                                                    </span>
                                                            </a>
                                                            <ul>
                                                                {foreach $Category.subcategories as $Subcat}
                                                                    <li class>
                                                                        <a href="/wow/ru/character/kazzak/Russianfur/achievement#{$Category.id}:{$Subcat.id}" class="" rel="np">
                                                                    <span class="arrow"><span class="icon">
                                                                        {$Subcat.name}
                                                                    </span></span>
                                                                        </a>
                                                                    </li>
                                                                {/foreach}
                                                            </ul>
                                                        </li>
                                                    {/if}
                                                {/if}
                                            {/foreach}
                                        </ul>
                                    </ul>
                                </div>
                                <div id="swipe-out-container" class="swipe-container"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-contents">
                    <div class="profile-section-header">
                        <div class="achievement-points-anchor">
                            <div class="achievement-points">
                                {$Character.apoints}
                            </div>
                        </div>
                        <ul class="profile-tabs">
                            <li class="tab-active">
                                <a href="achievement" rel="np">
                                    <span class="r"><span class="m">
                                        {#Profile_Character_Achievements#}
                                    </span></span>
                                </a>
                            </li>
                            <li>
                                <a href="statistic" rel="np">
                                    <span class="r"><span class="m">
                                        {#Profile_Character_Statistics#}
                                    </span></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="profile-section">
                        <div class="search-container keyword" id="search-container" style="display: none;">
                            <span class="view"></span>
                            <span class="reset" style="display: none;"></span>
                            <input type="text" id="achievement-search" alt="{#Search#}…" value="{#Search#}…" onkeyup="AchievementsHandler.doSearch(this.value)" class="input" autocomplete="off" />
                        </div>
                        <div id="cat-summary" class="container" style="display:block;">
                            <h3 class="category">{#Profile_Character_Progress#}</h3>
                            <div class="achievements-total">
                                <div class="profile-box-full">
                                    <div class="achievements-total-completed">
                                        <div class="desc">{#Profile_Achievements_Total_Received#}</div>
                                        {$CalculatePercentage = ($Character.acount / $AStatus.achievements_amount) * 100}
                                        <div class="profile-progress border-4" onmouseover="Tooltip.show(this, '{$Character.apoints} / {$AStatus.points_maximum} {#Profile_Achievements_Points#}', { location: 'middleRight' });">
                                            <div class="bar border-4 hover" style="width: {$CalculatePercentage|string_format:"%.2f"}%"></div>
                                            <div class="bar-contents">
                                                <strong>
                                                    {$Character.acount} / {$AStatus.achievements_amount} ({$CalculatePercentage|string_format:"%.2f"}%)
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="achievements-categories-total">
                                    {foreach $Categories as $Category}
                                        {if $Category.id != 1 && $Category.id != 81}
                                            {assign 'CompletedCount' '0'}
                                            {assign 'PointsCount' '0'}
                                            {foreach $CompletedAchievements as $Achievement}
                                                {if in_array($Achievement.category, $Category)}
                                                    {$CompletedCount = $CompletedCount + 1}
                                                    {$PointsCount = $PointsCount + $Achievement.points}
                                                {elseif isset($Category.subcategories)}
                                                    {if in_array($Achievement.category, $Category.subcategories)}
                                                        {$CompletedCount = $CompletedCount + 1}
                                                        {$PointsCount = $PointsCount + $Achievement.points}
                                                    {/if}
                                                {/if}
                                            {/foreach}
                                            {$BuildPercentage = ($CompletedCount / {$Category.achievements_in_category}) * 100}
                                            <div class="entry">
                                                <div class="entry-inner">
                                                    <strong class="desc">{$Category.name}</strong>
                                                    <div class="active-category" onclick="window.location.hash = '#{$Category.id}'; dm.openEntry(false)">
                                                        <div class="profile-progress border-4" onmouseover="Tooltip.show(this, '{$PointsCount} / {$Category.points_for_category} {#Profile_Achievements_Points#}', { location: 'middleRight' });">
                                                            <div class="bar border-4 hover" style="width: {$BuildPercentage|string_format:"%.2f"}%"></div>
                                                            <div class="bar-contents">
                                                                {$CompletedCount} / {$Category.achievements_in_category} ({$BuildPercentage|string_format:"%.2f"})
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        {elseif $Category.id == 81}
                                            <div class="entry">
                                                <div class="entry-inner">
                                                    <strong class="desc">{$Category.name}</strong>
                                                    <div class="active-category" onclick="window.location.hash = '#{$Category.id}'; dm.openEntry(false)">
                                                        <div class="profile-progress bar-contents border-4">
                                                            {assign 'CountFoS' '0'}
                                                            {foreach $CompletedAchievements as $Achievement}
                                                                {if $Achievement.category == 81}
                                                                    {$CountFoS = $CountFoS + 1}
                                                                {/if}
                                                            {/foreach}
                                                            {$CountFoS}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}
                                    </div>
                                </div>
                            </div>
                            <h3 class="category">{#Profile_Character_Achievements_Recent#}</h3>
                            <div class="achievements-recent profile-box-full">
                                <ul>
                                    <li>
                                        {assign 'LastFive' '0'}
                                        {foreach $CompletedAchievements as $Achievement}
                                            {if $LastFive == 5}
                                                {break}
                                            {else}
                                                {if $Achievement.parentAchievement != '-1'}
                                                    <a href="achievement#{$Achievement.parentAchievement}:{$Achievement.category}:a{$Achievement.achievement}" data-achievement="{$Achievement.achievement}" onclick="window.location.hash = '{$Achievement.parentAchievement}:{$Achievement.category}:a{$Achievement.achievement}'; dm.openEntry(false)" class=" clear-after">
                                                {else}
                                                    <a href="achievement#{$Achievement.category}:a{$Achievement.achievement}" data-achievement="{$Achievement.achievement}" onclick="window.location.hash = '{$Achievement.category}:a{$Achievement.achievement}'; dm.openEntry(false)" class=" clear-after">
                                                {/if}
                                                    <span class="float-right">
                                                        <span class="points">{$Achievement.points}</span>
                                                        <span class="date">{$Achievement.date|relative_date}</span>
                                                    </span>
                                                    <span class="icon">
                                                        <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Achievement.iconname|strtolower}.jpg&quot;);"></span>
                                                    </span>
                                                    <span class="info">
                                                        <strong class="title">{$Achievement.name}</strong>
                                                        <span class="description">{$Achievement.description}</span>
                                                    </span>
                                                </a>
                                                {$LastFive = $LastFive + 1}
                                            {/if}
                                        {/foreach}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div id="achievement-list" class="achievements-list">

                        </div>
                    </div>
                </div>
                <span class="clear">

                </span>
            </div>
            <script type="text/javascript">
                //<![CDATA[
                $(function() {
                    Profile.url = '/character/{$Character.name}/achievement';
                });
                //]]>
            </script>
            <script type="text/javascript">
                //<![CDATA[
                $(document).ready(function () {
                    DynamicMenu.init({ "section": "achievement" });
                    AchievementsHandler.init();
                })
                //]]>
            </script>
        </div>
    </div>
</div>
{include file="footer.tpl"}