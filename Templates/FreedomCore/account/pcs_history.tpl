{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div class="dashboard service">
                <div class="primary">
                    <div  class="header">
                        <h2 class="subcategory">{$Service.title}</h2>
                        <h3 class="headline">{$Service.history}</h3>
                        <a href="/account/management/dashboard?accountName=WoW{$Account.id}"><img src="/Templates/{$Template}/images/game-icons/wowx5.png" alt="World of Warcraft" width="48" height="48" /></a>
                    </div>
                    <div class="service-wrapper">
                        <p class="service-nav">
                            <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service={$Service.service}">{#Account_Management_Service#}</a>
                            <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service={$Service.service}&amp;servicecat=history" class="active">{#Account_Management_Service_History#}</a>
                            <a href="/account/management/dashboard?accountName=WoW{$Account.id}">{#Account_Management_Back_To_Account#}</a>
                        </p>
                        {if empty($Payments)}
                            <p class="no-service-history">Вы еще не пользовались этой услугой для персонажей на данной учетной записи.</p>
                        {/if}
                    </div>
                    {if !empty($Payments)}
                        <div class="payment-history">
                            <div class="table-section">
                                <div id="sub-payment-history">
                                    <div class="data-container type-table">
                                        <table class="payment-history-table" style="font-size: 14px!important;">
                                            <thead>
                                            <tr class="">
                                                <th>{#Date#}</th>
                                                <th>{#Summ#}</th>
                                                <th class="grid-70">{#Payment#}</th>
                                                <th>{#Status#}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {foreach $Payments as $Payment}
                                                <tr class="">
                                                    <td>
                                                        <span>
                                                            <time datetime="{$Payment.date}" data-format="dd/MM/yyyy">{$Payment.date|date_format:"%d/%e/%Y"}</time>
                                                        </span>
                                                    </td>
                                                    <td data-raw="{$Payment.price}">
                                                        {$Payment.price|string_format:"%.2f"} USD
                                                    </td>
                                                    <td>
                                                        {$Service.title}, {$Payment.price|string_format:"%.2f"} USD
                                                    </td>
                                                    <td>{$Payment.status}</td>
                                                </tr>
                                            {/foreach}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/if}
                 </div>
            </div>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}