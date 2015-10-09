<div class="service-interior light">
    <h3 class="headline">{#Account_Management_Service_Confirm_Changes#}:</h3>
    <div class="confirm-service">
        <span class="confirm-service-label pad-bottom">{#Account_Management_Service_New_Race#}:</span>
        <span class="confirm-service-details">
        {#Account_Management_Service_New_Race_Desc#}.<br />
        <em>
            {$smarty.config.Account_Management_Service_New_Race_Help|sprintf:$Character.side_translation:$Character.class_data.translation}
        </em>
        </span>
    </div>
    <span class="clear"><!-- --></span>
    {include file='account/service_confirm/proceed_to_payment.tpl'}
</div>