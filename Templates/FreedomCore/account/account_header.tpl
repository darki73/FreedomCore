{include file='account/account_head.tpl'}
<div id="nav-client-header" class="nav-client default">
    <div id="nav-client-bar">
        <div class="grid-container nav-header-content">
            <ul class="nav-list nav-left" id="nav-client-main-menu">
                <li>
                    <a id="nav-client-home" class="nav-item nav-home" href="/" data-analytics="global-nav" data-analytics-placement="Nav - {$AppName} Icon"></a>
                </li>
                <li>
                    <a id="nav-client-shop" class="nav-item nav-link" href="/shop" data-analytics="global-nav" data-analytics-placement="Nav - {#Menu_Shop#}">{#Menu_Shop#}</a>
                </li>
            </ul>
            {if !$smarty.session.loggedin}
                <ul class="nav-list nav-right" id="nav-client-account-menu">
                    <li>
                        <div id="username">
                            <div class="dropdown pull-right">
                                <a class="nav-link username dropdown-toggle" data-toggle="dropdown" rel="navbar">
                                    {#Account_Management#}
                                    <b class="caret"></b>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="arrow top"></div>
                                    <div class="user-profile">
                                        <div class="dropdown-section">
                                            <div class="nav-box">
                                                <a class="nav-item nav-btn nav-btn-block nav-login-btn" href="/account/login" data-analytics="global-nav" data-analytics-placement="Nav - Account - Log In">{#Login_Authorization#}</a>
                                            </div>
                                        </div>
                                        <div class="dropdown-section">
                                            <ul class="nav-list">
                                                <li>
                                                    <a class="nav-item nav-a nav-item-box" href="/account/management/" data-analytics="global-nav" data-analytics-placement="Nav - Account - Settings">
                                                        <i class="nav-icon-24-blue nav-icon-character-cog"></i>
                                                        {#Account_Parameters#}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a id="nav-client-support-link" class="nav-item nav-link" href="/support/" data-analytics="global-nav" data-analytics-placement="Nav - {#Support#}"> {#Support#} </a>
                    </li>
                </ul>
            {else}
                <ul class="nav-list nav-right" id="nav-client-account-menu">
                    <li>
                        <div id="username">
                            <div class="dropdown pull-right">
                                <a class="nav-link username dropdown-toggle" data-toggle="dropdown" rel="navbar">
                                    {$User.username}
                                    <b class="caret"></b>
                                </a>
                                <div class="dropdown-menu pull-right">
                                    <div class="arrow top"></div>
                                    <div class="user-profile">
                                        <div class="dropdown-section">
                                            {if $User.access_level == 4}
                                                <div class="dropdown-section">
                                                    <ul class="nav-list">
                                                        <li>
                                                            <a class="nav-item nav-a nav-item-box" href="/admin/dashboard" data-analytics="global-nav" data-analytics-placement="Nav - Account - Settings">
                                                                <i class="nav-icon-24-blue nav-icon-character-cog"></i>{#Administrator_Title#}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            {/if}
                                            <div class="nav-box selectable">
                                                {if $User.freedomtag_name != ''}
                                                    <div class="label">
                                                        <span class="battletag">{$User.freedomtag_name}</span>
                                                        <span class="code">#{$User.freedomtag_id}</span>
                                                    </div>
                                                {/if}
                                                <div class="email">{$User.email}</div>
                                            </div>
                                        </div>
                                        <div class="dropdown-section">
                                            <ul class="nav-list">
                                                <li>
                                                    <a class="nav-item nav-a nav-item-box" href="/account/management/" data-analytics="global-nav" data-analytics-placement="Nav - Account - Settings">
                                                        <i class="nav-icon-24-blue nav-icon-character-cog"></i>{#Account_Parameters#}</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="dropdown-section">
                                            <a class="nav-item nav-item-box" href="/account/signout" data-analytics="global-nav" data-analytics-placement="Nav - Account - Log Out"><i class="nav-icon-24-blue nav-icon-logout"></i>{#Login_Logout#}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a id="nav-client-support-link" class="nav-item nav-link" href="/support/" data-analytics="global-nav" data-analytics-placement="Nav - {#Support#}"> {#Support#} </a>
                    </li>
                </ul>
            {/if}
        </div>
    </div>
</div>

<div id="layout-top">
    <div class="wrapper">
        <div id="header">
            <div id="search-bar">
                <form action="/search" id="search-form" method="get">
                    <input type="hidden" id="csrftoken" name="csrftoken" value="625ea365-1f2f-418d-bdab-6eff64de74bb" />
                    <div>
                        <input accesskey="q" alt="{#Account_Search_Placeholder#} {$AppName}" id="search-field" maxlength="35" name="q" tabindex="50" type="text" value="{#Account_Search_Placeholder#} {$AppName}" />
                        <input id="search-button" title="{#Account_Search_Placeholder#} {$AppName}" tabindex="50" type="submit" value="" />
                    </div>
                </form>
            </div>
            <h1 id="logo"><a accesskey="h" href="/account/management" tabindex="50"></a></h1>
            <div id="navigation">
                <div id="page-menu" class="large">
                    <h2><a href="/account/management/"> {#Account_Management_M#}
                        </a></h2>
                    <ul>
                        <li {if $Page.type == 'account_management'}class="active"{/if}>
                            <a href="/account/management/" class="border-3">{#Account_Management_Information#}</a>
                            <span class="arrow"></span>
                        </li>
                        <li>
                            <a href="#" class="border-3 menu-arrow" onclick="openAccountDropdown(this, 'settings'); return false;">{#Account_Parameters#}</a>
                            <span class="arrow"></span>
                            <div class="flyout-menu" id="settings-menu" style="display: none">
                                <ul>
                                    <li><a href="/account/management/settings/change-email">{#Account_Management_Change_Email#}</a></li>
                                    <li><a href="/account/management/settings/change-password">{#Account_Management_Change_Email#}</a></li>
                                    <li><a href="/account/management/wallet">{#Account_Management_Payment_Methods#}</a></li>
                                    <li><a href="/account/management/primary-address">{#Account_Management_Contacts#}</a></li>
                                </ul>
                                <!--[if IE 6]>&#160;
                                <iframe id="settings-shim" src="javascript:false;" frameborder="0" scrolling="no" style="display: block; position: absolute; top: 0; left: 0; width: 200px; height: 220px; z-index: -1;"></iframe>
                                <script type="text/javascript">
                                    //<![CDATA[
                                    (function(){
                                    var doc = document;
                                    var shim = doc.getElementById('settings-shim');
                                    shim.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)';
                                    shim.style.display = 'block';
                                    })();
                                    //]]>
                                    </script>
                                    <![endif]-->
                            </div>
                        </li>
                        <li {if $Page.type == 'account_dashboard'}class="active"{/if}>
                            <a href="#" class="border-3 menu-arrow" onclick="openAccountDropdown(this, 'games'); return false;">{#Account_Management_Games_And_Codes#}</a>
                            <span class="arrow"></span>
                            <div class="flyout-menu" id="games-menu" style="display: none">
                                <ul>
                                    <li><a href="/account/management/claim-code">{#Account_Management_Claim_Code#}</a></li>
                                    <li><a href="/account/management/get-a-game">{#Account_Management_Get_A_Game#}</a></li>
                                    <li><a href="/account/management/download/">{#Account_Management_Download_Games#}</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="border-3 menu-arrow transaction" onclick="openAccountDropdown(this, 'activity'); return false;">{#Account_Management_Operations#}
                                <span id="chargebackCount" class="border-3" style="">0</span>
                            </a>
                            <span class="arrow"></span>
                            <div class="flyout-menu" id="activity-menu" style="display: none">
                                <ul>
                                    <li><a href="/account/management/orders">{#Account_Management_Orders_History#}</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="account-balance account-balance-rub" id="accountBalanceCenter" data-tooltip-options="{ldelim}&quot;location&quot;: &quot;mouse&quot;{rdelim}">
                            <a href="#" class="border-3 menu-arrow title" onclick="openAccountDropdown(this, 'accountBalance'); return false;">
                                <span class="sub-title">{#Account_Management_Wallet#}:</span><br />
                                <span class="balance" id="primary-balance">--,-- {$User.selected_currency} </span>
                            </a>
                            <div class="flyout-menu" id="accountBalance-menu">
                                <ul>
                                    <li id="RUB" class="switch-currency selected"><span>--,-- {$User.selected_currency} </span></li>
                                    <li class=" first"><a href="/shop/product/balance">{#Account_Management_Wallet_TopUP#}</a></li>
                                    <li class=" line "><a href="/account/management/transaction-history">{#Account_Management_Wallet_History#}</a></li>
                                    <li id="refreshBalance"><a href="#" onclick="accountBalance.refreshBalance(); return false;">{#Account_Management_Wallet_Refresh#}</a></li>
                                    <li class="refreshing" id="refreshingBalance"><a href="#" onclick="return false;"><img src="/Templates/{$Template}/images/icons/loader.gif" alt="" height="11" width="16" />{#Account_Management_Wallet_Refreshing#}</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <span class="clear"><!-- --></span>
                </div>

                <script type="text/javascript">
                    //<![CDATA[
                    $(function() {
                        accountBalance.accountBalanceCurrency = "{$User.selected_currency}";
                        accountBalance.currencyMap = {
                            'EUR' : {
                                'format': '{ldelim}0{rdelim} EUR',
                                'groupSize': 3,
                                'delimiter': ' ',
                                'point': ','
                            },
                            'GBP' : {
                                'format': '{ldelim}0{rdelim} GBP',
                                'groupSize': 3,
                                'delimiter': ' ',
                                'point': ','
                            },
                            'RUB' : {
                                'format': '{ldelim}0{rdelim} RUB',
                                'groupSize': 3,
                                'delimiter': ' ',
                                'point': ','
                            },
                            'USD' : {
                                'format': '{ldelim}0{rdelim} USD',
                                'groupSize': 3,
                                'delimiter': ' ',
                                'point': ','
                            }
                        };
                        accountBalance.initialize();
                        accountBalance.refreshBalance();
                        $('.account-balance-dialog').dialog({
                            autoOpen: false,
                            modal: true,
                            position: "center",
                            resizeable: false,
                            closeText: "{#Account_Close#}",
                            buttons: {
                                'Готово': function() {
                                    $(this).dialog("close");
                                }
                            },
                            open: function() {
                                $(".ui-dialog-buttonpane").find("button").addClass("button1").find(":first").addClass("first");
                                if(Core.browser=="ie6" || Core.browser=="ie7" || Core.browser=="ie8"){
                                    $(".ui-widget-overlay").css("opacity", 0.8);
                                }
                            }
                        });
                    });
                    //]]>
                </script>
                <span class="clear"><!-- --></span>
            </div>
        </div>
    </div>
</div>