{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div class="dashboard service">
                <div class="primary">
                    <div  class="header">
                        <h2 class="subcategory">{#Account_Management_Service_CS#}</h2>
                        <h3 class="headline">{$Service.title}</h3>
                        <a href="/account/management/dashboard?accountName=WoW{$Account.id}"><img src="/Templates/{$Template}/images/game-icons/wowx5.png" alt="World of Warcraft" width="48" height="48" /></a>
                    </div>
                    <div class="service-wrapper">
                        <p class="service-nav">
                            <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service={$Service.service}" class="active">{#Account_Management_Service#}</a>
                            <a href="/account/management/dashboard?accountName=WoW{$Account.id}">{#Account_Management_Back_To_Account#}</a>
                        </p>
                        <div id="page-content">
                            <div class="restoration-landing">
                                <p>{#Account_Management_Service_FIR_Description#}</p>
                                <p class="landing-subtitle">{#Account_Management_Service_FIR_WhatCanDo#}:</p>
                                <div class="region-eu">
                                    <div class="landing-feature">
                                        <div class="icon multiple"></div>
                                        <div class="title">{#Account_Management_Service_FIR_RestoreMany#}</div>
                                        <div class="desc">{#Account_Management_Service_FIR_RestoreMany_Desc#}</div>
                                    </div>
                                    <div class="landing-feature">
                                        <div class="icon availability"></div>
                                        <div class="title">{#Account_Management_Service_FIR_TDays#}</div>
                                        <div class="desc">{#Account_Management_Service_FIR_TDays_Desc#}</div>
                                    </div>
                                    <div class="landing-feature">
                                        <div class="icon instant"></div>
                                        <div class="title">{#Account_Management_Service_FIR_Instant#}</div>
                                        <div class="desc">{#Account_Management_Service_FIR_Instant_Desc#}</div>
                                    </div>
                                    <span class="clear"><!-- --></span>
                                </div>
                                <div class="landing-buttons">
                                    <div class="step-buttons">
                                        <a class="ui-button button1" href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service={$Service.service}&amp;servicecat=select-items&amp;character={$Character.name}" id="restoration-landing-submit">
                                            <span class="button-left">
                                                <span class="button-right">
                                                    {#Account_Management_Service_FIR_Begin#}
                                                </span>
                                            </span>
                                        </a>
                                        <a class="ui-cancel " href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service={$Service.service}">
                                            <span>
                                                {#Account_Management_Service_FIR_Cancel#}
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}