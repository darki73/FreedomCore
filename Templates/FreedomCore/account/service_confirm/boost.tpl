<div class="service-interior light">
    <h3 class="headline">{#Account_Management_Service_Confirm_Changes#}:</h3>
    <div class="confirm-service">
        <span class="confirm-service-label">{#Account_Management_Service_PCB_NewLevel#}:</span>
        <span class="confirm-service-details">80 {#LevelShort#} ({$Character.class_data.translation} - {$Character.side_translation})</span>
        <span class="confirm-service-label">{#Account_Management_Service_PCB_NewIlvl#}:</span>
        <span class="confirm-service-details"> 219</span>
        <span class="confirm-service-label pad-bottom">{#Account_Management_Service_PCB_SelectSpec#}:</span>
        <span class="confirm-service-details" style="color: #168bff;">
            {foreach $BoostItems as $Spec}
                {foreach $Spec as $Key=>$Value}
                    <input type="radio" name="specialization" value="{$Key}">{ucfirst(str_replace('_', ' ', $Key))}<br />
                {/foreach}
                {break}
            {/foreach}
        </span>
        {if $Character.level >= 60 && !empty($ProfessionsBoost)}
            <span class="confirm-service-label">{#Account_Management_Service_PCB_ProfBoost#}:</span>
            <span class="confirm-service-details">
                {foreach $ProfessionsBoost as $Profession}
                    {$Profession.profession_name} - {$Profession.new_max}<br />
                {/foreach}
            </span>
        {/if}
    </div>
    <span class="clear"><!-- --></span>
    {include file='account/service_confirm/proceed_to_payment.tpl'}
</div>