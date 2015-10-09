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
                                            {$smarty.config.Account_Management_Payment_New_Race_Help|sprintf:$NewFaction:$Character.class_data.translation}
                                        </em>
                                    </span>
    </div>
    <span class="clear"></span>
    {include file='account/service_confirm/proceed_to_payment.tpl'}
</div>