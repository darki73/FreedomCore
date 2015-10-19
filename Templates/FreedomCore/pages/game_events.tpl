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
                    <a href="/character/{$Character.name}/events" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Game_Events#}</span>
                    </a>
                </li>
            </ol>
        </div>
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
                            {#Game_Events#}
                        </h3>
                    </div>
                    <div class="profile-section events">
                        <ul class="event-list" id="event-list">
                            <li class="event-category category-secondary">
                                <h3 class="category ">{#Today#}</h3>
                                <div class="profile-box-simple">
                                    <ul>
                                        {foreach $Events.today as $Today}
                                            <li data-id="{$Today.eventEntry}" class="event-summary system-event">
                                                <div class="event-header">
                                                    <span class="icon-frame ">
                                                        <img src="/Templates/FreedomCore/images/calendar/calendar_weekendpvpskirmishstart.png" alt="" width="56" height="56" />
                                                        <span class="frame"></span>
                                                    </span>
                                                    <div class="event-header-text">
                                                        <h4 class="subheader name">{$Today.description}</h4>
                                                        <p class="time">
                                                            <span class="datetime">{$Today.next_start}</span>
                                                        </p>

                                                        <p class="time-relative">
                                                            <span class="datetime">
                                                                {$Today.next_start|date_format:"%d.%m"} - {$Today.next_end|date_format:"%d.%m"}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                            </li>
                            <li class="event-category category-secondary">
                                <h3 class="category ">{#Soon#}</h3>
                                <div class="profile-box-simple">
                                    <ul>
                                        {foreach $Events.soon as $Soon}
                                            <li data-id="{$Soon.eventEntry}" class="event-summary system-event">
                                                <div class="event-header">
                                                    <span class="icon-frame ">
                                                        <img src="/Templates/FreedomCore/images/calendar/calendar_weekendpvpskirmishstart.png" alt="" width="56" height="56" />
                                                        <span class="frame"></span>
                                                    </span>
                                                    <div class="event-header-text">
                                                        <h4 class="subheader name">{$Soon.description}</h4>
                                                        <p class="time">
                                                            <span class="datetime">{$Soon.next_start}</span>
                                                        </p>

                                                        <p class="time-relative">
                                                            <span class="datetime">
                                                                {$Soon.next_start|date_format:"%d.%m"} - {$Soon.next_end|date_format:"%d.%m"}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                            </li>
                        </ul>
                        <span class="clear"></span>
                    </div>
                </div>
            </div>
            <span class="clear"></span>
        </div>
        <script type="text/javascript">
            //<![CDATA[
            $(function() {
                Profile.url = '/character/{$Character.name}/events';
            });

        </script>
    </div>
</div>
{include file="footer.tpl"}