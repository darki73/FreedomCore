{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div id="lobby">
                <div id="page-content" class="page-content">
                    <div id="lobby-account">
                        <h3 class="section-title">{#Account_Management_Account_Information#}</h3>
                        <div class="lobby-box">
                            <h4 class="subcategory">{#Account_Management_Account_Name#}</h4>
                            <p>{$User.email}
                                <span class="edit">[<a href="/account/management/settings/change-email">{#Account_Management_Parameter_Change#}</a>]</span>
                            </p>
                            <h4 class="subcategory help-link-right" data-tooltip="{#Account_Management_FreedomTag_Tooltip#} {$AppName}" data-tooltip-options="{ldelim}&quot;location&quot;: &quot;mouse&quot;{rdelim}">FreedomTag®</h4>
                            {if $User.freedomtag_name != ''}
                                <p>
                                    {$User.freedomtag_name}#{$User.freedomtag_id}
                                    <span class="edit">[<a href="/account/management/freedomtag-modify">{#Account_Management_Parameter_Change#}</a>]</span>
                                </p>
                            {else}
                                <p>
                                    <a href="/account/management/freedomtag-create">{#Account_Management_FreedomTag_Create#}</a>
                                </p>
                            {/if}
                            <h3 class="section-title">{#Account_Management_Account_Security#}</h3>
                            <h4 class="subcategory">{#Account_Management_Security_Email#}</h4>
                            <p class="has-authenticator-has-active">
                                {#Account_Management_Security_Email_Ver#}
                            </p>
                        </div>
                    </div>
                    <div id="lobby-games">
                        <h3 class="section-title">{#Account_Management_Accounts_For_Games#}</h3>
                        <div id="games-list">
                            <a href="#wow" class="border-2 games-title opened" rel="game-list-wow">WoW</a>
                            <ul id="game-list-wow">
                                {foreach from=$Accounts item=Account key=i}
                                    <li class="border-4" id="WoW{$Account.id}::EU">
                                        <span class="game-icon">
                                            <span class="png-fix"><img src="/Templates/{$Template}/images/game-icons/wow-32.png" alt="" width="32" height="32" /></span>
                                        </span>
                                        <span class="account-info">
                                            <span class="account-link">
                                                <strong>
                                                    <a class="EU-WOW-wod-de" href="/account/management/dashboard?accountName=WoW{$Account.id}">
                                                        World of Warcraft®: {$Account.expansion_name}™
                                                    </a>
                                                </strong>
                                                <span class="account-id">
                                                    [WoW{$Account.id}]
                                                    <span class="account-edition">{#Account_Management_Digital_Edition#}</span>
                                                    <span class="account-status ACTIVE">{#Account_Management_WoW_Active#}</span>
                                                </span>
                                                <span class="account-region">{#Account_Management_WoW_Europe#}</span>
                                            </span>
                                        </span>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>
                        <div id="games-tools">
                            <a href="/account/management/claim-code" id="add-game" class="border-5">{#Account_Management_Claim_Code#}</a>
                            <p>
                                <a href="/account/management/get-a-game" class="" onclick="">
                                    <span class="icon-16 icon-account-buy"></span>
                                    <span class="icon-16-label">{#Account_Management_Get_A_Game#}</span>
                                </a>
                            </p>
                            <p>
                                <a href="/account/management/download" class="" onclick="">
                                    <span class="icon-16 icon-account-download"></span>
                                    <span class="icon-16-label">{#Account_Management_Download_Games#}</span>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}