{if isset($smarty.session.loggedin) || $smarty.session.loggedin}
<div  class="sidebar-module " id="sidebar-events">
    <div class="sidebar-title">
        <h3 class="header-3 title-events">
            {foreach $Characters as $Character}
                {if $User.pinned_character != null && $Character.guid == $User.pinned_character}
                <a href="/character/{$Character.name}/events">
                    {#Guild_Events#}
                </a>
                {/if}
            {/foreach}
        </h3>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-events">
            <h4>{#Today#}</h4>
            <ul class="sidebar-list today">
                {if count($Events) == 0}
                    No events
                {else}
                    {foreach $Events as $Event}
                        <li data-id="{$Event.eventEntry}" class="event-summary sidebar-tile system-event">
                            <a href="#" rel="np">
                                <span class="icon-frame ">
                                    <img src="/Templates/FreedomCore/images/calendar/calendar_weekendpvpskirmishstart.png" alt="" width="27" height="27" />
                                    <span class="frame"></span>
                                </span>

                                <span class="info-wrapper clear-after">
                                    <span class="date date-status">
                                        00:00
                                    </span>
                                    <span class="title">{$Event.description}</span>
                                    <span class="date">
                                            {$Event.next_start|date_format:"%d.%m"} - {$Event.next_end|date_format:"%d.%m"}
                                    </span>
                                </span>
                                <span class="clear"><!-- --></span>
                            </a>
                        </li>
                        <span class="clear"><!-- --></span>
                    {/foreach}
                {/if}
            </ul>

        </div>

    </div>
</div>
{/if}