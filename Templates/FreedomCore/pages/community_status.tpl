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
                    <a href="/community" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Menu_Community#}</span>
                    </a>
                </li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/community/status" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Community_Realms_Status_Title#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div class="content-header">
                <h2 class="header ">{#Community_Realms_Status_Title#}</h2>
                <div class="desc">{#Community_Realms_Status_Full_Desc#}</div>
                <span class="clear"><!-- --></span>
            </div>
            <div id="realm-status">
                <ul class="tab-menu ">
                    <li>
                        <a href="javascript:;" class="tab-active">
                            {#Community_Realms_Show_All_Realms#}
                        </a>
                    </li>
                </ul>
                <div class="filter-toggle">
                    <a href="javascript:;" class="selected" onclick="RealmStatus.filterToggle(this)">
                        <span style="display: none">{#Community_Realms_Filter_Show#}</span>
                        <span>{#Community_Realms_Filter_Hide#}</span>
                    </a>
                </div>
                <span class="clear"><!-- --></span>
                <div id="realm-filters" class="table-filters">
                    <form action="">
                        <div class="filter">
                            <label for="filter-status">{#Community_Realms_State#}</label>
                            <select id="filter-status" class="input select" data-filter="column" data-column="0">
                                <option value="">{#Community_Realms_Filter_All#}</option>
                                <option value="up">{#Community_Realms_Filter_Online#}</option>
                                <option value="down">{#Community_Realms_Filter_Offline#}</option>
                            </select>
                        </div>
                        <div class="filter">
                            <label for="filter-name">{#Community_Realms_Realm_Name#}</label>
                            <input type="text" class="input" id="filter-name" data-filter="column" data-column="1" />
                        </div>
                        <div class="filter">
                            <label for="filter-type">{#Community_Realms_Type#}</label>

                            <select id="filter-type" class="input select" data-filter="column" data-column="2">
                                <option value="">{#Community_Realms_Filter_All#}</option>
                                <option value="rp">
                                    {#Community_Realms_Filter_RP#}
                                </option>
                                <option value="rppvp">
                                    {#Community_Realms_Filter_RPPVP#}
                                </option>
                                <option value="pve">
                                    {#Community_Realms_Filter_PVE#}
                                </option>
                                <option value="pvp">
                                    {#Community_Realms_Filter_PVP#}
                                </option>
                            </select>
                        </div>
                        <div class="filter">
                            <label for="filter-population">{#Community_Realms_Population#}</label>
                            <select id="filter-population" class="input select" data-filter="column" data-column="3">
                                <option value="">{#Community_Realms_Filter_All#}</option>
                                <option value="high">{#Community_Realms_Filter_High#}</option>
                                <option value="low">{#Community_Realms_Filter_Low#}</option>
                                <option value="medium">{#Community_Realms_Filter_Medium#}</option>
                            </select>
                        </div>
                        <div class="filter" id="locale-filter">
                            <label for="filter-locale">{#Community_Realms_Language#}</label>
                            <select id="filter-locale" class="input select" data-column="4" data-filter="column">
                                <option value="">{#Community_Realms_Filter_All#}</option>
                                <option value="{#Community_Realms_Filter_Russian#}">{#Community_Realms_Filter_Russian#}</option>
                                <option value="{#Community_Realms_Filter_English#}">{#Community_Realms_Filter_English#}</option>
                                <option value="{#Community_Realms_Filter_German#}">{#Community_Realms_Filter_German#}</option>
                                <option value="{#Community_Realms_Filter_Spanish#}">{#Community_Realms_Filter_Spanish#}</option>
                                <option value="{#Community_Realms_Filter_Development#}">{#Community_Realms_Filter_Development#}</option>
                            </select>
                        </div>
                        <div class="filter">
                            <label for="filter-queue">{#Community_Realms_Queue#}</label>
                            <input type="checkbox" id="filter-queue" class="input" value="true" data-column="5" data-filter="column" />
                        </div>
                        <div class="filter" style="margin: 5px 0 5px 15px">
                            <button class="ui-button button1" type="button" id="filter-button" onclick="RealmStatus.reset();"><span class="button-left"><span class="button-right">{#Community_Realms_Clear_Filter#}</span></span></button>
                        </div>
                        <span class="clear"><!-- --></span>
                    </form>
                </div>
            </div>
            <span class="clear"><!-- --></span>
            <div id="all-realms">
                <div class="table full-width data-container type-table">
                    <table>
                        <thead>
                        <tr>
                            <th><a href="javascript:;" class="sort-link"><span class="arrow">{#Community_Realms_State#}</span></a></th>
                            <th><a href="javascript:;" class="sort-link"><span class="arrow up">{#Community_Realms_Realm_Name#}</span></a></th>
                            <th><a href="javascript:;" class="sort-link"><span class="arrow">{#Community_Realms_Type#}</span></a></th>
                            <th><a href="javascript:;" class="sort-link"><span class="arrow">{#Community_Realms_Population#}</span></a></th>
                            <th><a href="javascript:;" class="sort-link"><span class="arrow">{#Community_Realms_Language#}</span></a></th>
                            <th><a href="javascript:;" class="sort-link"><span class="arrow">{#Community_Realms_Queue#}</span></a></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $Realms as $Realm}
                            <tr class="row1">
                                <td class="status" data-raw="{$Realm.status}">
                                    <div class="status-icon {$Realm.status}" {if $Realm.status == "up"} data-tooltip="{#Community_Realms_Filter_Online#}" {else} data-tooltip="{#Community_Realms_Filter_Offline#}" {/if}>
                                    </div>
                                </td>
                                <td class="name">
                                    {$Realm.name}
                                </td>
                                <td data-raw="{$Realm.type|lower}" class="type">
                                    <span class="{$Realm.type|lower}">
                                            ({$Realm.type})
                                    </span>
                                </td>
                                <td class="population" data-raw="high">
									{if $Realm.population <= 0.5}
                                    <span class="low">
                                        {#Community_Realms_Filter_Low#}
                                    {elseif $Realm.population > 0.5 && $Realm.population <= 1.0}
                                    <span class="medium">
                                        {#Community_Realms_Filter_Medium#}
                                    {elseif $Realm.population > 1.0 && $Realm.population <= 2.0}
                                    <span class="high">
                                        {#Community_Realms_Filter_High#}
									{/if}
							</span>
                                </td>
                                <td class="locale">
                                   {$Realm.language}
                                </td>
                                <td data-raw="false" class="queue">
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
            <span class="clear"><!-- --></span>
        </div>
    </div>
</div>
{include file='footer.tpl'}