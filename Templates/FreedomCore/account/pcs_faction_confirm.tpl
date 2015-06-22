{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div class="dashboard service">
                <div class="primary">
                    <div  class="header">
                        <h2 class="subcategory">{#Account_Management_Service_CS#}</h2>
                        <h3 class="headline">{#Account_Management_Service_PFC#}</h3>
                        <a href="/account/management/dashboard?accountName=WoW{$Account.id}"><img src="/Templates/{$Template}/images/game-icons/wowx5.png" alt="World of Warcraft" width="48" height="48" /></a>
                    </div>
                    <div class="service-wrapper">
                        <p class="service-nav">
                            <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PFC" class="active">{#Account_Management_Service#}</a>
                            <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PFC&amp;servicecat=history">{#Account_Management_Service_History#}</a>
                            <a href="/account/management/dashboard?accountName=WoW{$Account.id}">{#Account_Management_Back_To_Account#}</a>
                        </p>
                        <div class="service-info">
                            <div class="service-tag">
                                <div class="service-tag-contents border-3">
                                    <div class="character-icon wow-portrait-64-80 wow-{$Character.gender}-{$Character.race}-{$Character.class} glow-shadow-3">
                                        <img src="/Templates/{$Template}/images/2d/avatar/{$Character.race}-{$Character.gender}.jpg" width="64" height="64" alt="" />
                                    </div>
                                    <div class="service-tag-description">
                                        <span class="character-name caption">
                                            {$Character.name}
                                        </span>
                                        <span class="character-class">
                                            {#LevelShort#} {$Character.level} {$Character.race_data.translation} {$Character.class_data.translation}
                                        </span>
                                    </div>
                                    <span class="clear"><!-- --></span>
                                </div>
                            </div>
                            <div class="service-summary">
                                <p class="service-price headline">{$Service.price|string_format:"%.2f"} USD
                                </p>
                                <p class="service-memo">{#Account_Management_Service_Waiting_Time#}</p>
                            </div>
                        </div>
                        <div class="service-form">
                            <div class="service-interior light">
                                <h3 class="headline">{#Account_Management_Service_Confirm_Changes#}:</h3>
                                <div class="confirm-service">
                                    <span class="confirm-service-label" style="line-height:40px;">{#Account_Management_Service_New_Faction#}:</span>
                                    {assign 'NewFaction' ''}
                                    {if $Character.side_id == 1}
                                        <span class="confirm-service-details" style="line-height:40px;">
                                            <img src="/Templates/{$Template}/images/services/wow/alliance_banner.png" alt="" />
                                                {#Account_Management_Service_New_Faction_Alliance#}
                                                {$NewFaction = $smarty.config.Account_Management_Service_New_Faction_Alliance}
                                        </span>
                                    {else}
                                        <span class="confirm-service-details" style="line-height:40px;">
                                            <img src="/Templates/{$Template}/images/services/wow/horde_banner.png" alt="" />
                                            {#Account_Management_Service_New_Faction_Horde#}
                                            {$NewFaction = $smarty.config.Account_Management_Service_New_Faction_Horde}
                                        </span>
                                    {/if}
                                    <span class="clear"></span>
                                    <span class="confirm-service-label pad-bottom">{#Race#}:</span>
                                    <span class="confirm-service-details">
                                        {#Account_Management_Service_New_Faction_LogInToChange_Title#}<br />
                                        <em>
                                            {$smarty.config.Account_Management_Service_New_Faction_LogInToChange_Description|sprintf:$NewFaction:$Character.class_data.translation}
                                        </em>
                                    </span>
                                </div>
                                <span class="clear"></span>
                                <form method="POST" action="/account/management/payment/pay?accountName=WoW{$Account.id}&character={$Character.name}&service={$Service.name}">
                                    <div class="service-interior light">
                                        <fieldset class="ui-controls section-stacked override">
                                            <button class="ui-button button1" type="submit" tabindex="1">
                                                <span class="button-left">
                                                    <span class="button-right">
                                                        {#Account_Management_Service_Proceed_To_Payment#}
                                                    </span>
                                                </span>
                                            </button>
                                            <a class="ui-cancel " href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PFC&amp;servicecat=tos&amp;character={$Character.name}" tabindex="1">
                                            <span>
                                                {#Account_Management_Service_ToS_Decline#}
                                            </span>
                                            </a>
                                        </fieldset>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <span class="clear"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}