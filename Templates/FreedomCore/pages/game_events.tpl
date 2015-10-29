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
                    <a href="/game/events" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Events_Page_Title#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div class="events-main-menu">
                <div class="wrapper">
                    <ul class="events-list">
                        {foreach $Events as $Event}
                            {if isset($Event.description)}
                                {if
                                    strstr($Event.description, '(') === false &&
                                    strstr($Event.description, ':') === false &&
                                    strstr($Event.link, 'fishing') === false
                                }
                                    <li class="events-list-item">
                                        <a class="events-list-link" href="/game/events/{$Event.link}">
                                            <span class="events-icon icon-{$Event.link}"></span>
                                            <span class="events-title">{$Event.description}</span>
                                            <span class="events-date">
                                                {$Event.next_start|date_format: "%d.%m"} - {$Event.next_end|date_format: "%d.%m"}
                                            </span>
                                            <span class="clear"><!-- --></span>
                                        </a>
                                    </li>
                                {/if}
                            {/if}
                        {/foreach}
                        <li class="events-list-item">
                            <a class="events-list-link" href="/game/events/new-year">
                                <span class="events-icon icon-new-year"></span>
                                <span class="events-title">{#Events_Page_New_Year#}</span>
                                <span class="events-date">
                                    31.12
                                </span>
                                <span class="clear"><!-- --></span>
                            </a>
                        </li>
                        <li class="events-list-item">
                            <a class="events-list-link" href="/game/events/stranglethorn-fishing-extravaganza">
                                <span class="events-icon icon-stranglethorn-fishing-extravaganza"></span>
                                <span class="events-title">{#Events_Page_Fishing_Ext#}</span>
                                <span class="events-date">
                                    {#Events_Page_Weekly#}
                                </span>
                                <span class="clear"><!-- --></span>
                            </a>
                        </li>
                        <li class="events-list-item">
                            <a class="events-list-link" href="/game/events/darkmoon-faire">
                                <span class="events-icon icon-darkmoon-faire"></span>
                                <span class="events-title">{#Events_Page_Faire#}</span>
                                <span class="events-date">
                                    {#Events_Page_Monthly#}
                                </span>
                                <span class="clear"><!-- --></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="events-contents">
                <div class="events-intro">
                    <h2 class="events-header">{#Events_Page_Title#}</h2>
                    <p class="events-intro-text">
                        {#Events_Page_Intro#}
                    </p>
                    {if !empty($CurrentEvent)}
                        <h3 class="events-subheader">{#Events_Page_Current_Event#}</h3>
                        <div class="panel panel-{$CurrentEvent.link}" style="width: 60%;">
                            <h3 class="panel-title">{$CurrentEvent.description}</h3>
                            <h4 class="panel-subtitle">
                                {$CurrentEvent.next_start|date_format: "%d.%m.%Y"} - {$CurrentEvent.next_end|date_format: "%d.%m.%Y"}
                            </h4>
                            <p class="panel-desc">
                                {$CurrentEvent.description_text}
                            </p>
                            <a class="ui-button button1" href="/game/events/{$CurrentEvent.link}">
                                <span class="button-left">
                                    <span class="button-right">{#Events_Page_Learn_More#}</span>
                                </span>
                            </a>
                        </div>
                    {/if}
                    <h3 class="events-subheader">{#Events_Page_Repeating_Events#}</h3>
                    <div class="panel panel-stranglethorn-fishing-extravaganza" style="width: 60%;">
                        <h3 class="panel-title">{#Events_Page_Fishing_Ext#}</h3>
                        <h4 class="panel-subtitle">{#Events_Page_Every_Sunday#}</h4>
                        <p class="panel-desc">
                            {#Events_Page_Fishing_Ext_Desc#}
                        </p>
                        <a class="ui-button button1" href="/game/events/stranglethorn-fishing-extravaganza">
                            <span class="button-left">
                                <span class="button-right">
                                    {#Events_Page_Learn_More#}
                                </span>
                            </span>
                        </a>
                    </div>
                    <div  class="panel panel-darkmoon-faire" style="width: 60%;">
                        <h3 class="panel-title">{#Events_Page_Faire#}</h3>
                        <h4 class="panel-subtitle">{#Events_Page_Month#}</h4>
                        <p class="panel-desc">
                            {#Events_Page_Faire_Desc#}
                        </p>

                        <a class="ui-button button1" href="/game/events/darkmoon-faire">
                            <span class="button-left">
                                <span class="button-right">
                                    {#Events_Page_Learn_More#}
                                </span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <span class="clear"></span>
        </div>
    </div>
</div>
{include file="footer.tpl"}