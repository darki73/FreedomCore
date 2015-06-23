{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div class="dashboard service">
                <div class="primary">
                    <div  class="header">
                        <h2 class="subcategory">{#Account_Management_Service_RR#}</h2>
                        <h3 class="headline">{$Service.title}</h3>
                        <a href="/account/management/dashboard?accountName=WoW{$Account.id}"><img src="/Templates/{$Template}/images/game-icons/wowx5.png" alt="World of Warcraft" width="48" height="48" /></a>
                    </div>
                    <div class="service-wrapper">
                        <p class="service-nav">
                            <a href="/account/management/services/referrals?accountName=WoW{$Account.id}&amp;service={$Service.service}">{#Account_Management_Service#}</a>
                            <a href="/account/management/services/referrals?accountName=WoW{$Account.id}&amp;service={$Service.service}&amp;servicecat=history">{#Account_Management_Service_History#}</a>
                            <a href="/account/management/dashboard?accountName=WoW{$Account.id}">{#Account_Management_Back_To_Account#}</a>
                            <a href="/account/management/services/referrals?accountName=WoW{$Account.id}&amp;service={$Service.service}&amp;servicecat=description" class="active">Итак, что за чем?..</a>
                        </p>
                        <div class="instructions">
                            <h3 class="caption">{#Account_Management_Service_RAF_Help_Header#}</h3>
                            <p class="margin-bottom">{#Account_Management_Service_RAF_Help_SubHeader#}</p>
                            <h3 class="caption">{#Account_Management_Service_RAF_Help_Why_Header#}</h3>
                            <div class="diagram">
                                <div class="container-left">
                                    <div class="left">
                                        <span class="number">1</span>
                                        <div class="intro">{#Account_Management_Service_RAF_Help_Solo_Intro#}</div>
                                        <div class="describe">{#Account_Management_Service_RAF_Help_Solo_Description#}</div>
                                        <span class="more-info" onclick="GuildServices.toggleInstructions('left');">{#Account_Management_Service_RAF_Help_More#}</span>
                                    </div>
                                </div>
                                <div class="container-right">
                                    <div class="right">
                                        <span class="number">2</span>
                                        <div class="intro">{#Account_Management_Service_RAF_Help_Many_Intro#}</div>
                                        <div class="describe">{#Account_Management_Service_RAF_Help_Many_Description#}</div>
                                        <span class="more-info" onclick="GuildServices.toggleInstructions('right');">{#Account_Management_Service_RAF_Help_More#}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="clear"><!-- --></span>
                            <div class="button-container">
                                <a class="ui-button button1" href="/account/management/services/referrals?accountName=WoW{$Account.id}&amp;service={$Service.service}" id="go" tabindex="1">
                                    <span class="button-left">
                                        <span class="button-right">
                                            {#Account_Management_Service_RAF_Help_Invite#}
                                        </span>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <span class="clear"><!-- --></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}