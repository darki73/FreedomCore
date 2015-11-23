{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div id="page-header">
                <span class="float-right"><span class="form-req">*</span>{#Account_Management_Payment_Must_FillIn#}</span>
                <h2 class="subcategory">{#Account_Management_M#}</h2>
                <h3 class="headline">{#Account_Management_Payment_Pay#}</h3>
            </div>
            <form method="post" id="payment-form" action="/account/management/payment/complete_payment" onsubmit="return FormHandler.handleSubmit('submit', this, '{#Account_Management_Payment_OneMinute#}', 'submitDisabled');">
                <div id="payment-wrapper" class="clear-after">
                    <div class="purchase-overview purchase-overview-usd">
                        <h3>{#Account_Management_Payment_Info#}</h3>
                        <div class="content">
                            <div class="item last-item">
                                <span class="thumb">
                                <img src="/Templates/{$Template}/images/services/wow/{$Service.name}.png" alt="{$Service.title}" title="{$Service.title}" />
                                </span>
                                <div class="product-detail clear-after">
                                    {assign 'ServiceName' 'Account_Management_Service_'|cat:strtoupper($Service.name)}
                                    {assign 'ServiceDescription' 'Account_Management_Service_'|cat:strtoupper($Service.name):'_Description'}
                                    <h4>{$smarty.config.$ServiceName}</h4>
                                    <p class="description">{$smarty.config.$ServiceDescription}</p>
                                </div>
                                <span class="clear"><!-- --></span>
                                <div class="detail">
                                    {#Community_Character#}
                                    <strong>{$Character.name}</strong>
                                </div>
                                {if $Service.name == 'pcb'}
                                    <input type="hidden" name="specialization" id="specialization" value="{$specialization}" />
                                {/if}

                                {if $Service.name == 'pfc'}
                                    <div class="detail">
                                        {#Account_Management_Old_Faction#}
                                        <strong>{$Character.side_translation}</strong>
                                    </div>
                                    <div class="detail">
                                        {#Account_Management_New_Faction#}
                                        {if $Character.side_id == '0'}
                                            <strong>{#Account_Management_Service_New_Faction_Horde#}</strong>
                                        {else}
                                            <strong>{#Account_Management_Service_New_Faction_Alliance#}</strong>
                                        {/if}
                                    </div>
                                {else}
                                    <div class="detail">
                                        {#Character_Faction#}
                                        <strong>{$Character.side_translation}</strong>
                                    </div>
                                {/if}
                                <div class="detail form-row" style="text-align: right;">
                                    <a class="add-label" style="margin-top:10px" id="edit-order-link" href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PFC&amp;servicecat=tos&amp;character={$Character.name}">
                                        {#Account_Management_Payment_Edit#}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="total-price">
                            <div class="total-due">
                                {#Account_Management_Payment_Total#}:<strong>{$Service.price|string_format:"%.2f"} USD</strong>
                            </div>
                        </div>
                    </div>
                    <div class="payment-overview">
                        <div id="payment-toggle">
                            <div class="section-header drop-shadow border-4">
                                Платежная информация
                            </div>
                            <div class="section-box border-4">
                                <div id="payment-form">
                                    <div class="form-row">
                                        <span class="form-left">
                                            {#Account_Management_Service#}
                                        </span>
                                        <span class="form-right">
                                            {$Service.title}
                                        </span>
                                    </div>
                                    <div class="form-row">
                                        <span class="form-left">
                                            {#Account_Management_Payment_PayingFrom#}
                                        </span>
                                        <span class="form-right">
                                            {$User.username}
                                        </span>
                                    </div>
                                    <div class="form-row">
                                        <span class="form-left">
                                            {#Account_Management_Payment_Method#}
                                        </span>
                                        <span class="form-right">
                                            {#Account_Management_Payment_Wallet#}
                                        </span>
                                    </div>
                                    <div class="form-row">
                                        <span class="form-left">
                                            {#Account_Management_Payment_Remaining_Balance#}
                                        </span>
                                        <span class="form-right">
                                            {$Balance = $AccountBalance.balance - $Service.price}
                                            {if $Balance < 0}
                                                <font color="red">{$Balance} USD</font>
                                            {else}
                                                <font color="green">{$Balance} USD</font>
                                            {/if}
                                        </span>
                                    </div>
                                </div>
                                <span class="clear"></span>
                            </div>
                            <div class="submit-container">
                                <div class="total-due-submit">
                                    <div class="form-row">
                                        <span class="form-left">
                                            {#Account_Management_Payment_Total#}:
                                        </span>
                                        <span class="form-right">
                                                <strong class="supporting" id="totalSubmit">{$Service.price|string_format:"%.2f"} USD</strong><br />
                                        </span>
                                    </div>
                                </div>
                                {if $Balance < 0}
                                    {#Account_Management_Payment_InsufFunds#}
                                {else}
                                    <input type="hidden" name="accountName" value="WoW{$Account.id}">
                                    <input type="hidden" name="service" value="{$Service.service}">
                                    <input type="hidden" name="character" value="{$Character.name}">
                                    <input type="hidden" name="newbalance" value="{$Balance}">
                                    <input type="hidden" name="price" value="{$Service.price}">
                                    <fieldset class="ui-controls section-buttons">
                                        <button class="ui-button button1" type="submit" id="submitted" tabindex="1">
                                            <span class="button-left">
                                                <span class="button-right">{#Account_Management_Payment_Pay#}</span>
                                            </span>
                                        </button>
                                        <a class="ui-cancel " href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PFC&amp;servicecat=tos&amp;character={$Character.name}" id="payment-cancel" tabindex="1">
                                            <span>
                                                {#Account_Management_Service_ToS_Decline#}
                                            </span>
                                        </a>
                                    </fieldset>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}