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
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/game/events" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Events_Page_Title#}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/game/events/{$Event.link}" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$Event.name}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <style>
                .content-bot { padding-bottom: 102px; }
            </style>
            <div class="event">
                <style>
                    .content-top { background: url(/Templates/{$Template}/images/calendar/{$Event.link}-background.jpg) 100% 0 no-repeat; }
                </style>
                <div class="content-header">
                    <p class="events-parent"><a href="./">{#Events_Page_InGame#}</a></p>
                    <h2 class="events-header">{$Event.name}</h2>
                    <h3 class="events-subheader">
                        {if isset($DData.next_start)}
                            {$DData.next_start|date_format: "%d.%m.%Y"} - {$DData.next_end|date_format: "%d.%m.%Y"}
                        {/if}
                        {if $Event.link == 'new-year'}
                            31.12.{'Y'|date}
                        {/if}
                        {if $Event.link == 'stranglethorn-fishing-extravaganza'}
                            {#Events_Page_Weekly#}
                        {/if}
                        {if $Event.link == 'darkmoon-faire'}
                            {#Events_Page_Month#}
                        {/if}
                    </h3>
                    {if $Event.event_quests != null || $Event.event_entertainment != null || $Event.event_merchants != null}
                    <div class="page-outline">
                        <h4 class="section-title">
                            <a class="section-link" href="#activities">{#Events_Page_What_To_Do#}</a>
                        </h4>
                        <p class="subsection-list">
                            {if $Event.event_quests != null}
                                <a href="#quests">{#Events_Page_Cat_Quests#}</a>
                            {/if}
                            {if $Event.event_entertainment != null}
                                <a href="#entertainment">
                                    {if $Event.event_quests != null}
                                    ,
                                    {/if}
                                    {#Events_Page_Cat_Entertainment#}
                                </a>
                            {/if}
                            {if $Event.event_merchants != null}
                                {if $Event.event_entertainment != null}
                                    ,
                                {/if}
                                <a href="#merchants-and-vendors">{#Events_Page_Cat_Merchants#}</a>
                            {/if}
                        </p>
                    </div>
                    {/if}
                    {if !empty($Event.collectibles.Mounts) || !empty($Event.collectibles.Pets) || !empty($Event.collectibles.Items) || !empty($Event.collectibles.Toys)}
                    <div class="page-outline">
                        <h4 class="section-title">
                            <a class="section-link" href="#rewards">
                                {#Events_Page_Rewards#}
                            </a>
                        </h4>
                        <p class="subsection-list">
                            {if !empty($Event.collectibles.Mounts)}
                                <a href="#mounts">
                                    {#Events_Page_Cat_Mounts#}
                                </a>
                            {/if}
                            {if !empty($Event.collectibles.Pets)}
                                <a href="#pets">
                                    {if !empty($Event.collectibles.Mounts)}
                                    ,
                                    {/if}
                                    {#Events_Page_Cat_Pets#}
                                </a>
                            {/if}
                            {if !empty($Event.collectibles.Items)}
                                <a href="#gear">
                                    {if !empty($Event.collectibles.Pets)}
                                        ,
                                    {/if}
                                    {#Events_Page_Cat_Items#}
                                </a>
                            {/if}
                            {if !empty($Event.collectibles.Toys)}
                                <a href="#toys">
                                    {if !empty($Event.collectibles.Items) || !empty($Event.collectibles.Pets)}
                                        ,
                                    {/if}
                                    {#Events_Page_Cat_Toys#}
                                </a>
                            {/if}
                        </p>
                    </div>
                    {/if}
                    {if isset($Event.achievements)}
                    <div class="page-outline">
                        <h4 class="section-title">
                            <a class="section-link" href="#achievements">
                                {#Events_Page_Achievements#}
                            </a>
                        </h4>
                    </div>
                    {/if}
                </div>
                <div class="event-content">
                    <div class="left-col">
                        <div id="intro" class="events-section">
                            <h3 class="events-subheader">
                                {$Event.intro_header}
                            </h3>
                            <p class="events-intro-text">
                                {$Event.intro_footer}
                            </p>
                        </div>
                        {if $Event.event_quests != null || $Event.event_entertainment != null || $Event.event_merchants != null}
                        <div id="activities" class="events-section">
                            <h3 class="events-subheader has-top-link">
                                {#Events_Page_What_To_Do#}
                                <a class="top-link" href="#">
                                    {#Events_Page_Go_To_Top#}
                                </a>
                            </h3>
                            <p>
                                {$Event.what_to_do}
                            </p>
                            {if $Event.event_quests != null}
                                <div id="quests" class="events-subsection subsection-quests">
                                    <h4 class="subsection-title">
                                        {#Events_Page_Cat_Quests#}
                                    </h4>
                                    {$Event.event_quests}
                                </div>
                            {/if}
                            {if $Event.event_entertainment != null}
                                <div id="entertainment" class="events-subsection subsection-entertainment">
                                    <h4 class="subsection-title">{#Events_Page_Cat_Entertainment#}</h4>
                                    {$Event.event_entertainment}
                                </div>
                            {/if}
                            {if $Event.event_merchants != null}
                                <div id="merchants-and-vendors" class="events-subsection subsection-merchants-and-vendors">
                                    <h4 class="subsection-title">{#Events_Page_Cat_Merchants#}</h4>
                                    {$Event.event_merchants}
                                </div>
                            {/if}
                        </div>
                        {/if}
                        {if !empty($Event.collectibles.Mounts) || !empty($Event.collectibles.Pets) || !empty($Event.collectibles.Items) || !empty($Event.collectibles.Toys)}
                        <div id="rewards" class="events-section">
                            <h3 class="events-subheader has-top-link">
                                {#Events_Page_Rewards#}
                                <a class="top-link" href="#">
                                    {#Events_Page_Go_To_Top#}
                                </a>
                            </h3>
                            <p>
                                {#Events_Page_Rewards_Desc#}
                            </p>
                            {if !empty($Event.collectibles.Mounts)}
                                <div id="mounts" class="events-subsection subsection-mounts">
                                    <h4 class="subsection-title">{#Events_Page_Cat_Mounts#}</h4>
                                    {foreach $Event.collectibles.Mounts as $Mount}
                                        <h5 class="item-title">
                                            <a class="item-link color-q{$Mount.Quality}" href="/item/{$Mount.entry}" data-item="{$Mount.entry}">
                                                <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Mount.icon}.jpg&quot;);"></span>
                                                {$Mount.name}
                                            </a>
                                        </h5>
                                    {/foreach}
                                </div>
                            {/if}
                            {if !empty($Event.collectibles.Pets)}
                                <div id="pets" class="events-subsection subsection-pets">
                                    <h4 class="subsection-title">{#Events_Page_Cat_Pets#}</h4>
                                    {foreach $Event.collectibles.Pets as $Pet}
                                        <h5 class="item-title">
                                            <a class="item-link color-q{$Pet.Quality}" href="/item/{$Pet.entry}" data-item="{$Pet.entry}">
                                                <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Pet.icon}.jpg&quot;);"></span>
                                                {$Pet.name}
                                            </a>
                                        </h5>
                                    {/foreach}
                                </div>
                            {/if}
                            {if !empty($Event.collectibles.Items)}
                                <div id="gear" class="events-subsection subsection-gear">
                                    <h4 class="subsection-title">{#Events_Page_Cat_Items#}</h4>
                                    {foreach $Event.collectibles.Items as $Item}
                                        <h5 class="item-title">
                                            <a class="item-link color-q{$Item.Quality}" href="/item/{$Item.entry}" data-item="{$Item.entry}">
                                                <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Item.icon}.jpg&quot;);"></span>
                                                {$Item.name}
                                            </a>
                                        </h5>
                                    {/foreach}
                                </div>
                            {/if}
                            {if !empty($Event.collectibles.Toys)}
                                <div id="toys" class="events-subsection subsection-toys">
                                    <h4 class="subsection-title">{#Events_Page_Cat_Toys#}</h4>
                                    {foreach $Event.collectibles.Toys as $Toy}
                                        <h5 class="item-title">
                                            <a class="item-link color-q{$Toy.Quality}" href="/item/{$Toy.entry}" data-item="{$Toy.entry}">
                                                <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Toy.icon}.jpg&quot;);"></span>
                                                {$Toy.name}
                                            </a>
                                        </h5>
                                    {/foreach}
                                </div>
                            {/if}
                        </div>
                        {/if}
                        {if isset($Event.achievements)}
                            <div id="achievements" class="events-section">
                                <h3 class="events-subheader has-top-link">
                                    {#Events_Page_Achievements#}
                                    <a class="top-link" href="#">
                                        {#Events_Page_Go_To_Top#}
                                    </a>
                                </h3>
                                <div class="panel panel-achievements">
                                    <div class="achievements-desc">
                                        <p>
                                            TEST TEST
                                        </p>
                                    </div>
                                    <div class="table">
                                        <table>
                                            <tbody>
                                            {foreach $Event.achievements as $Achievement}
                                                <tr>
                                                    <td>
                                                        <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Achievement.icon}.jpg&quot;);"></span>
                                                        <span class="achievement-link" data-achievement="{$Achievement.id}">{$Achievement.name_loc0}</span>
                                                        {$Achievement.points}<span class="icon-achievement-points"></span>
                                                    </td>
                                                </tr>
                                            {/foreach}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        {/if}
                    </div>
                    <div class="sidebar">
                        <div class="events-sidebar-section section-event-location">
                            <h3 class="events-sidebar-header">{#Events_Page_Event_Place#}</h3>
                            <p>
                                {$Event.event_place}
                            </p>
                        </div>
                        {if $Event.event_currency_present == 1}
                            <div class="events-sidebar-section section-currency">
                                <h3 class="events-sidebar-header">{#Events_Page_Event_Currency#}</h3>
                                <p>
                                {$Event.event_currency}
                                </p>
                            </div>
                        {/if}
                    </div>
                    <span class="clear"></span>
                </div>
            </div>
            <span class="clear"></span>
        </div>
    </div>
</div>
{include file="footer.tpl"}